<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DashboardModel;
use App\Models\StudyModel;
use App\Models\CourseModel;
use App\Models\PaymantModel;

class BillController extends Controller
{
    public function billpage()
    {
        $session = session();
        $user_id = $session->get('id');
    
        if (!$user_id) {
            return redirect()->to('/login');
        }
    
        $dashboardModel = new DashboardModel();
        $courseModel = new CourseModel();
        $studyModel = new StudyModel(); // โมเดลสำหรับดึงข้อมูลนักเรียน
    
        // ดึงข้อมูลทั้งหมดของเทอม
        $terms = $dashboardModel->findAll();
        $courses = $courseModel->where('id_user', $user_id)->findAll();
        $isLoggedIn = $session->get('isLoggedIn') ?? false;
    
        // ตรวจสอบว่าแต่ละเทอมมีข้อมูลนักเรียนหรือไม่
        $validTerms = [];
        foreach ($terms as $term) {
            $studentsInTerm = $studyModel->where('ID_Terms', $term['id'])->findAll();
            if (!empty($studentsInTerm)) {
                $validTerms[] = $term; // ถ้ามีข้อมูลนักเรียนในเทอมนี้ให้เพิ่มในรายการ
            }
        }
    
        // ส่งข้อมูลไปยัง view
        $data = [
            'terms' => $validTerms, // ส่งเฉพาะเทอมที่มีข้อมูลนักเรียน
            'courses' => $courses,
            'isLoggedIn' => $isLoggedIn,
        ];
    
        if (empty($validTerms)) {
            $data['terms_message'] = 'ไม่มีข้อมูลนักเรียนในเทอมใดๆ';
        }
    
        if (empty($courses)) {
            $data['courses_message'] = 'ยังไม่มีข้อมูลคอร์สเรียน';
        }
    
        return view('billpage', $data);
    }
    

    public function printBill()
    {
        $term_id = $this->request->getVar('term_id');
    
        // ตรวจสอบว่า term_id มีค่าหรือไม่
        if (!$term_id) {
            return redirect()->to('/selectTerm')->with('error', 'กรุณาเลือกเทอม');
        }
    
        // ดึงข้อมูลใบเสร็จตาม term_id
        $studyModel = new StudyModel();
        $query = $studyModel->select('
            study.ID_Study, 
            study.Firstname_S, 
            study.Lastname_S, 
            study.balance,
            course.Course_name, 
            course.Price_DC, 
            study.Total, 
            study.Status_Price
        ')
        ->join('course', 'study.ID_Courses = course.id', 'left')
        ->where('study.ID_Terms', $term_id)
        ->orderBy('course.Course_name')  // เรียงตามชื่อคอร์ส
        ->findAll();
    
        // ตรวจสอบว่า query มีข้อมูลหรือไม่
        if (empty($query)) {
            // ถ้าไม่มีข้อมูลในเทอมนี้ ให้แสดงข้อความแจ้งเตือน
            return view('printBill', ['error' => 'ไม่มีข้อมูลนักเรียนในเทอมนี้']);
        }
    
        // ส่งข้อมูลไปยัง view
        return view('printBill', ['studyData' => $query]);
    }
    




    public function fetchStudys()
    {
        $request = $this->request;
        $studyModel = new StudyModel();

        try {
            $start = (int) $request->getVar('start', FILTER_SANITIZE_NUMBER_INT);
            $length = (int) $request->getVar('length', FILTER_SANITIZE_NUMBER_INT);
            $search = esc($request->getVar('search')['value'] ?? '');
            $orderColumnIndex = (int) ($request->getVar('order')[0]['column'] ?? 0);
            $orderDir = ($request->getVar('order')[0]['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
            $termId = $this->request->getPost('term_id'); // รับค่า term_id

            // ถ้ามีการเลือก term_id
            $query = $studyModel->select('study.ID_Study, study.Firstname_S, study.Lastname_S, study.Total, 
                course.Course_name, course.Price_DC, study.Status_Price, term_course.Term_name, study.HowToPay, study.balance, 
                COUNT(*) as count_students, SUM(study.Total) as sumTotal, SUM(course.Price_DC) as sumPrice_DC')
                ->join('course', 'study.ID_Courses = course.id', 'left')
                ->join('term_course', 'study.ID_Terms = term_course.id', 'left');

            // ถ้ามีการกรองตาม term_id
            if ($termId) {
                $query->where('study.ID_Terms', $termId);
            }

            $query = $query->groupStart()
                ->like('study.Firstname_S', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในชื่อ
                ->orLike('study.Lastname_S', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในนามสกุล
                ->orLike('course.Price_DC', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในยอดเงิน
                ->orLike('course.Course_name', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในชื่อคอร์ส
                ->orLike('term_course.Term_name', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในเทอม
                ->groupEnd()
                ->orderBy($this->getColumnName($orderColumnIndex), $orderDir)
                ->groupBy('study.ID_Terms, study.ID_Courses') // ใช้ groupBy เพื่อรวมยอดเงินตามเทอมและคอร์ส
                ->findAll($length, $start);

            $totalData = $studyModel->countAll();

            // กรองจำนวนข้อมูลทั้งหมด
            $totalFiltered = $studyModel->select('study.ID_Study')
                ->join('course', 'study.ID_Courses = course.id', 'left')
                ->join('term_course', 'study.ID_Terms = term_course.id', 'left');

            // ถ้ามีการกรองตาม term_id
            if ($termId) {
                $totalFiltered->where('study.ID_Terms', $termId);
            }

            $totalFiltered = $totalFiltered->groupStart()
                ->like('study.Firstname_S', $search)
                ->orLike('study.Lastname_S', $search)
                ->orLike('course.Price_DC', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในยอดเงิน
                ->orLike('course.Course_name', $search)
                ->orLike('term_course.Term_name', $search)
                ->groupEnd()
                ->countAllResults();

            $data = [];
            foreach ($query as $row) {
                // คำนวณค้างชำระ (sumPrice_DC - sumTotal)
                $dueAmount = $row['sumPrice_DC'] - $row['sumTotal'];

                $data[] = [
                    '<input type="checkbox" class="row-checkbox" value="' . $row['ID_Study'] . '">',
                    $row['ID_Study'],
                    $row['Term_name'],
                    $row['Course_name'],
                    $row['Price_DC'] . ' บาท',
                    $row['count_students'],
                    $row['sumPrice_DC'] . ' บาท',
                    $dueAmount . ' บาท', // คอลัมน์ค้างชำระ
                ];
            }

            return $this->response->setJSON([
                "draw" => intval($request->getVar('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $data
            ]);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON(["error" => $e->getMessage()]);
        }
    }


    public function getColumnName($index)
    {
        $columns = [
            'ID_Study',
            'Firstname_S',
            'Lastname_S',
            'Term_name',
            'Course_name',
            'Total',
            'Status_Price',
            'HowToPay'
        ];

        return $columns[$index] ?? 'ID_Study';
    }

    public function confirmPayment()
    {
        $studyModel = new PaymantModel();
        $selectedIds = $this->request->getPost('selectedIds');
        $paymentType = $this->request->getPost('paymentType');

        if (!is_array($selectedIds) || empty($selectedIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณาเลือกข้อมูลก่อนยืนยัน']);
        }

        if (!in_array($paymentType, ['transfer', 'cash'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'วิธีการชำระเงินไม่ถูกต้อง']);
        }

        $invalidItems = $studyModel->whereIn('ID_Study', $selectedIds)
            ->where('HowToPay IS NOT NULL')
            ->countAllResults();

        if ($invalidItems > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'บางรายการมีการเลือกวิธีการชำระเงินแล้วและไม่สามารถแก้ไขได้']);
        }

        $studyModel->whereIn('ID_Study', $selectedIds)
            ->set(['HowToPay' => $paymentType])
            ->update();

        return $this->response->setJSON(['success' => true, 'message' => 'ยืนยันการชำระเงินสำเร็จ']);
    }

    public function printReceipt()
    {
        $studyModel = new StudyModel();
        $ids = $this->request->getGet('ids');

        if (!$ids) {
            return "ไม่พบข้อมูลใบเสร็จ!";
        }

        $idArray = array_filter(explode(',', $ids));

        // เช็คสถานะการชำระเงินก่อน
        $studies = $studyModel->whereIn('ID_Study', $idArray)->findAll();

        // ตรวจสอบว่าแต่ละรายการมีสถานะการชำระเงินเป็น 'เต็มจำนวน' หรือไม่
        foreach ($studies as $study) {
            if ($study['Status_Price'] !== 'full') {
                return "ไม่สามารถปริ้นใบเสร็จได้ เนื่องจากยังไม่ได้ชำระเต็มจำนวน";
            }
        }

        // หากทุกรายการมีสถานะเป็นชำระเต็มจำนวน ให้แสดงใบเสร็จ
        $data['receipts'] = $studies;
        return view('receipt_view', $data);
    }

    public function addPaymentAmount()
    {
        try {
            $amount = $this->request->getPost('paymentAmount');
            $study_ids = $this->request->getPost('study_ids'); // รับค่า study_ids ที่เป็น array
    
            log_message('debug', 'Received Study IDs: ' . implode(',', $study_ids)); // ตรวจสอบค่า study_ids
    
            if (empty($amount) || empty($study_ids)) {
                log_message('error', 'ข้อมูลไม่ถูกต้อง: paymentAmount หรือ study_ids ว่าง');
                return $this->response->setJSON(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
            }
    
            // คำสั่งที่ใช้ในการอัปเดตข้อมูล
            $studyModel = new StudyModel();
            $courseModel = new CourseModel(); // ใช้สำหรับดึงข้อมูล Price_DC
    
            foreach ($study_ids as $study_id) {
                $study = $studyModel->find($study_id);
    
                if (!$study) {
                    log_message('error', 'ไม่พบข้อมูลสำหรับ ID_Study: ' . $study_id);
                    continue;
                }
    
                // ตรวจสอบสถานะของการชำระเงิน
                if ($study['Status_Price'] === 'full') {
                    log_message('info', 'ไม่สามารถเพิ่มยอดชำระสำหรับ ID_Study: ' . $study_id . ' เนื่องจากสถานะเป็น "full"');
                    return $this->response->setJSON(['success' => false, 'message' => 'ได้ชำระครบแล้ว']);
                }
    
                // ดึงข้อมูลราคา Price_DC จากตาราง course
                $course = $courseModel->find($study['ID_Courses']);
    
                if (!$course) {
                    log_message('error', 'ไม่พบข้อมูลสำหรับ Course ID: ' . $study['ID_Courses']);
                    continue;
                }
    
                $priceDC = floatval($course['Price_DC']); // ราคาเต็มของคอร์ส
                $currentTotal = floatval($study['Total']); // ยอดชำระปัจจุบัน
                $currentBalance = floatval($study['balance']); // ยอดคงเหลือปัจจุบัน
    
                // คำนวณ Total ใหม่
                $newTotal = $currentTotal + floatval($amount);
    
                // ตรวจสอบว่า newTotal จะเกิน Price_DC หรือไม่
                if ($newTotal > $priceDC) {
                    log_message('info', 'ยอดชำระเกินราคาสำหรับ ID_Study: ' . $study_id);
                    return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถชำระเกินราคาครอสได้']);
                }
    
                // คำนวณ balance ใหม่
                $newBalance = max($currentBalance - floatval($amount), 0); // ห้ามติดลบ
    
                // อัปเดตค่า Total และ balance
                $updateData = [
                    'Total' => $newTotal,
                    'balance' => $newBalance
                ];
    
                // ถ้า Total ถึง Price_DC แล้ว ให้เปลี่ยนสถานะเป็น full
                if ($newTotal >= $priceDC) {
                    $updateData['Status_Price'] = 'full';
                }
    
                // อัปเดตข้อมูลในฐานข้อมูล
                if (!$studyModel->update($study_id, $updateData)) {
                    log_message('error', 'ไม่สามารถอัปเดตข้อมูลสำหรับ ID_Study: ' . $study_id);
                    continue;
                }
    
                log_message('info', 'อัปเดตยอดชำระสำเร็จสำหรับ ID_Study: ' . $study_id);
            }
    
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            log_message('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
        }
    }
    


    public function payMore()
    {
        $studyModel = new StudyModel();
        $id = $this->request->getPost('id');
        $amount = floatval($this->request->getPost('amount'));
        $method = $this->request->getPost('method');

        if (!$id || !$amount || $amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'ข้อมูลไม่ถูกต้อง']);
        }

        $record = $studyModel->find($id);
        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูล']);
        }

        // คำนวณยอดที่ชำระไปแล้ว
        $totalPaid = floatval($record['Total'] ?? 0) + $amount;
        $remainingAmount = floatval($record['PaidAmount'] ?? 0) + $amount;

        // คำนวณยอดคงเหลือหลังจากการจ่าย
        $remainingBalance = floatval($record['balance'] ?? 0) - $amount;

        // อัปเดตฐานข้อมูลก่อน
        $studyModel->update($id, [
            'Total' => $totalPaid,
            'PaidAmount' => $remainingAmount,
            'balance' => $remainingBalance, // อัปเดต balance
            'HowToPay' => $method
        ]);

        // เช็คยอดคงเหลือหลังจากการจ่าย
        if ($remainingBalance <= 0) {
            // หากยอดคงเหลือ <= 0 แสดงว่าสถานะต้องเป็น 'ชำระเต็มจำนวน'
            $studyModel->update($id, [
                'Status_Price' => 'full',
            ]);
        } else {
            // หากยังเหลืออยู่ให้เป็น 'มัดจำ'
            $studyModel->update($id, [
                'Status_Price' => 'deposit',
            ]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'อัปเดตการชำระเงินสำเร็จ']);
    }


    public function getRemainingAmount($id)
    {
        $studyModel = new StudyModel();
        $record = $studyModel->find($id);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูล']);
        }

        // ใช้ balance เป็นยอดคงเหลือ
        $remaining = floatval($record['balance']);

        return $this->response->setJSON(['success' => true, 'remaining' => $remaining]);
    }

    public function generateExcel($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('study');
        $builder->select('study.*, course.Course_name, term_course.Term_name, study.Status_Price');
        $builder->join('course', 'study.ID_Courses = course.id', 'left');
        $builder->join('term_course', 'study.ID_Terms = term_course.id', 'left');
        $builder->where('study.ID_Study', $id);
        $receiptData = $builder->get()->getRowArray();

        if (!$receiptData) {
            return "ไม่พบข้อมูลใบเสร็จ!";
        }

        // ใช้ match ในการแปลงค่าของ Status_Price เป็นข้อความ
        $statusText = match (strtolower(trim($receiptData['Status_Price'] ?? ''))) {
            'full' => 'ชำระเต็มจำนวน',
            'deposit' => 'มัดจำ',
            default => 'ไม่ทราบสถานะ',
        };

        // ตรวจสอบสถานะการจ่าย หากเป็น "มัดจำ" จะแสดง alert
        if ($statusText === 'มัดจำ') {
            echo "<script>alert('ไม่สามารถปริ้นใบเสร็จได้ เนื่องจากยังชำระไม่ครบจำนวน!'); window.history.back();</script>";
            exit;
        }

        // โหลดไฟล์ Excel ที่ออกแบบไว้
        $filePath = ROOTPATH . 'public/assets/excels/recipt.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // เติมข้อมูลลงไปในเซลล์
        $sheet->setCellValue('C12', $receiptData['Firstname_S'] . ' ' . $receiptData['Lastname_S']);
        $sheet->setCellValue('F18', $receiptData['Total'] . ' ');
        $sheet->setCellValue('C18', ' ' . '-' . ' ' . $receiptData['Term_name'] . "\n" . $receiptData['Course_name']);
        $sheet->getStyle('C18')->getAlignment()->setWrapText(true);
        $sheet->setCellValue('G10', date('Y-m-d H:i:s')); // ตั้งค่าวันที่และเวลา

        // ซ่อนแถวที่ 36
        $sheet->getRowDimension(36)->setVisible(false);

        // ซ่อนคอลัมน์ H
        $sheet->getColumnDimension('H')->setVisible(false);

        // ตั้งค่าขนาดของหน้าให้พอดี
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT); // ตั้งค่าทิศทางแนวตั้ง
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4); // ตั้งค่าขนาดเป็น A4

        // ปรับขนาดของหน้าให้พอดีกับเนื้อหา (เพื่อไม่ให้มีหน้าเปล่า)
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(1); // ตั้งค่าให้เนื้อหาพอดีกับหน้าเดียว

        // ใช้ Dompdf ในการแปลง Excel เป็น PDF
        $pdfWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Tcpdf'); // เปลี่ยนเป็น Dompdf

        // กำหนดให้ใช้ฟอนต์ที่เหมาะสม (เช่น 'THSarabunNew') หรือฟอนต์ที่ติดตั้งแล้ว
        $spreadsheet->getActiveSheet()->getStyle('C18')->getFont()->setName('THSarabunNew');
        $spreadsheet->getActiveSheet()->getStyle('C18')->getFont()->setSize(12); // ปรับขนาดฟอนต์ให้เหมาะสม

        // ตั้งค่า header สำหรับไฟล์ PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="receipt_' . $id . '.pdf"');
        header('Cache-Control: max-age=0');

        // แปลงไฟล์ Excel เป็น PDF และส่งออก
        $pdfWriter->save('php://output');
        exit;
    }
}
