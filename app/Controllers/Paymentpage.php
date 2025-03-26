<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DashboardModel;
use App\Models\StudyModel;
use App\Models\CourseModel;
use App\Models\paymantmodel;

class Paymentpage extends Controller
{


    public function paymentpage()
    {
        $session = session();
        $user_id = $session->get('id');

        if (!$user_id) {
            return redirect()->to('/login');
        }

        $dashboardModel = new DashboardModel();
        $courseModel = new CourseModel();

        $data['terms'] = $dashboardModel->where('user_id', $user_id)->findAll();
        $data['courses'] = $courseModel->where('id_user', $user_id)->findAll();
        $data['isLoggedIn'] = $session->get('isLoggedIn') ?? false;
        $data['isManager'] = (session()->get('role') == 'manager');  // ส่งค่า isManager ไปที่ view

        if (empty($data['terms'])) {
            $data['terms_message'] = 'ยังไม่มีข้อมูลเทอม';
        }

        if (empty($data['courses'])) {
            $data['courses_message'] = 'ยังไม่มีข้อมูลคอร์สเรียน';
        }

        return view('paymentpage', $data);
    }




    public function billpage()
    {
        $session = session();
        $user_id = $session->get('id');

        if (!$user_id) {
            return redirect()->to('/login');
        }

        $dashboardModel = new DashboardModel();
        $courseModel = new CourseModel();

        $data['terms'] = $dashboardModel->where('user_id', $user_id)->findAll();
        $data['courses'] = $courseModel->where('id_user', $user_id)->findAll();
        $data['isLoggedIn'] = $session->get('isLoggedIn') ?? false;

        if (empty($data['terms'])) {
            $data['terms_message'] = 'ยังไม่มีข้อมูลเทอม';
        }

        if (empty($data['courses'])) {
            $data['courses_message'] = 'ยังไม่มีข้อมูลคอร์สเรียน';
        }

        return view('billpage', $data);
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

            $query = $studyModel->select('study.ID_Study, study.Firstname_S, study.Lastname_S, study.Total, 
            course.Course_name, study.Status_Price, term_course.Term_name, study.HowToPay, study.balance')
                ->join('course', 'study.ID_Courses = course.id', 'left')
                ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
                ->groupStart()
                ->like('study.Firstname_S', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในชื่อ
                ->orLike('study.Lastname_S', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในนามสกุล
                ->orLike('study.Total', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในยอดเงิน
                ->orLike('course.Course_name', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในชื่อคอร์ส
                ->orLike('term_course.Term_name', $search) // ค้นหาคำที่ตรงหรือมีคำค้นในเทอม
                ->groupEnd()
                ->orderBy($this->getColumnName($orderColumnIndex), $orderDir)
                ->findAll($length, $start);

            $totalData = $studyModel->countAll();

            $totalFiltered = $studyModel->select('study.ID_Study')
                ->join('course', 'study.ID_Courses = course.id', 'left')
                ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
                ->groupStart()
                ->like('study.Firstname_S', $search)
                ->orLike('study.Lastname_S', $search)
                ->orLike('study.Total', $search)
                ->orLike('course.Course_name', $search)
                ->orLike('term_course.Term_name', $search)
                ->groupEnd()
                ->countAllResults();

            $data = [];
            foreach ($query as $row) {
                $statusText = match ($row['Status_Price']) {
                    'full' => 'ชำระเต็มจำนวน',
                    'deposit' => 'มัดจำ',
                    default => 'ไม่ทราบสถานะ',
                };

                // กำหนดสีของปุ่ม Edit ตามวิธีการชำระเงิน
                $statusClass = 'btn-secondary'; // สีเทา (ยังไม่มีสถานะ)
                if ($row['HowToPay'] == 'transfer') {
                    $statusClass = 'btn-primary'; // สีฟ้า (โอน)
                } elseif ($row['HowToPay'] == 'cash') {
                    $statusClass = 'btn-success'; // สีเขียว (เงินสด)
                }

                $data[] = [
                    '<input type="checkbox" class="row-checkbox" value="' . $row['ID_Study'] . '">', // ✅ Checkbox
                    $row['ID_Study'],
                    $row['Firstname_S'] . ' ' . $row['Lastname_S'],
                    $row['Term_name'],
                    $row['Course_name'],
                    $row['Total'] . ' บาท',
                    $statusText,
                    // เพิ่มปุ่ม Edit เป็นลูกวงกลม
                    '<button class="btn ' . $statusClass . ' btn-circle btn-sm"></button>'

                ];
            }

            $response = [
                "draw" => intval($request->getVar('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $data
            ];

            log_message('debug', json_encode($response, JSON_PRETTY_PRINT)); // ✅ Debug JSON
            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage()); // ✅ บันทึกข้อผิดพลาด
            return $this->response->setJSON(["error" => $e->getMessage()]);
        }
    }
    public function deleteStudy()
    {
        $id = $this->request->getPost('id');

        // สร้าง instance ของ StudyModel
        $studyModel = new StudyModel();

        // ลบข้อมูล
        if ($studyModel->deleteStudy($id)) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }


    public function getColumnName($index)
    {
        // สร้าง array ที่แมปจาก column index ไปเป็นชื่อคอลัมน์ในฐานข้อมูล
        $columns = [
            'ID_Study', // index 0
            'Firstname_S', // index 1
            'Lastname_S', // index 2
            'Term_name', // index 3
            'Course_name', // index 4
            'Total', // index 5
            'Status_Price', // index 6
            'HowToPay' // index 7
        ];

        // ถ้าค่าของ index อยู่ใน array ให้คืนค่าชื่อคอลัมน์
        return isset($columns[$index]) ? $columns[$index] : 'ID_Study'; // ถ้าไม่ได้กำหนดคอลัมน์ใน array ก็ใช้ 'ID_Study' เป็นค่าเริ่มต้น
    }


    public function confirmPayment()
    {
        $studyModel = new PaymantModel(); // เปลี่ยนชื่อจาก StudyModel เป็น PaymantModel
        $selectedIds = $this->request->getPost('selectedIds');
        $paymentType = $this->request->getPost('paymentType');

        // ตรวจสอบว่ามีการเลือกวิธีการชำระเงิน
        if ($paymentType !== 'transfer' && $paymentType !== 'cash') {
            return $this->response->setJSON(['success' => false, 'message' => 'วิธีการชำระเงินไม่ถูกต้อง']);
        }

        // ตรวจสอบว่ามีการเลือกข้อมูลหรือไม่
        if (!empty($selectedIds)) {
            // ตรวจสอบว่า ID_Study ที่เลือกมีค่า HowToPay เป็น NULL หรือไม่
            // นับจำนวนที่มี HowToPay เป็น NULL
            $invalidItems = $studyModel->whereIn('ID_Study', $selectedIds)
                ->where('HowToPay IS NOT NULL')  // ตรวจสอบว่ามีการระบุ HowToPay แล้ว
                ->countAllResults();

            if ($invalidItems > 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'บางรายการมีการเลือกวิธีการชำระเงินแล้วและไม่สามารถแก้ไขได้']);
            }

            // อัปเดตฟิลด์ HowToPay
            $studyModel->whereIn('ID_Study', $selectedIds)
                ->set(['HowToPay' => $paymentType]) // อัปเดตฟิลด์ HowToPay เท่านั้น
                ->update();

            return $this->response->setJSON(['success' => true, 'message' => 'ยืนยันการชำระเงินสำเร็จ']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'กรุณาเลือกข้อมูลก่อนยืนยัน']);
    }



    public function printReceipt()
    {
        $studyModel = new StudyModel();
        $ids = $this->request->getGet('ids');

        if (!$ids) {
            return "ไม่พบข้อมูลใบเสร็จ!";
        }

        // แปลง IDs เป็นอาร์เรย์
        $idArray = explode(',', $ids);

        // ดึงข้อมูลใบเสร็จหลายรายการ
        $data['receipts'] = $studyModel->whereIn('ID_Study', $idArray)->findAll();

        return view('receipt_view', $data);
    }
}
