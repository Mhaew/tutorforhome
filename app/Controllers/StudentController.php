<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DashboardModel;
use App\Models\StudyModel;
use App\Models\CourseModel;
use TCPDF;

class StudentController extends Controller
{
    public function studentpage()
    {
        $session = session();
        $user_id = $session->get('id');
    
        if (!$user_id) {
            return redirect()->to('/login');
        }
    
        $dashboardModel = new DashboardModel();
        $courseModel = new CourseModel();
        $studyModel = new StudyModel(); // โมเดลที่ใช้ในการดึงข้อมูลนักเรียน
    
        // ดึงข้อมูลทั้งหมดของเทอม
        $terms = $dashboardModel->findAll();
        $courses = $courseModel->where('id_user', $user_id)->findAll();
        $isLoggedIn = $session->get('isLoggedIn') ?? false;
    
        // ตรวจสอบว่าแต่ละเทอมมีข้อมูลนักเรียนหรือไม่
        $validTerms = [];
        foreach ($terms as $term) {
            // ตรวจสอบว่ามีนักเรียนในเทอมนี้หรือไม่
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
    
        return view('studentpage', $data);
    }
    

    public function printStudent()
    {
        $term_id = $this->request->getVar('term_id');  // รับค่า term_id จาก URL

        // ดึงข้อมูลนักเรียนที่ลงทะเบียนในเทอมที่เลือก และเรียงตามชื่อคอร์ส
        $studyModel = new StudyModel();  // ใช้ Model ชื่อ StudyModel
        $study = $studyModel->select('
                study.ID_Study,
                course.Course_name, 
                study.Firstname_S, 
                study.Lastname_S, 
                study.Phone_S, 
                study.Firstname_P, 
                study.Lastname_P, 
                study.Phone_P
            ')  // เลือกคอลัมน์ที่ต้องการ
            ->join('course', 'study.ID_Courses = course.id', 'left')  // เชื่อมกับตาราง course
            ->where('study.ID_Terms', $term_id)  // กรองตาม term_id
            ->orderBy('course.Course_name')  // เรียงตามชื่อคอร์ส
            ->findAll();

        // ส่งข้อมูลนักเรียนไปยัง view
        return view('printStudent', ['study' => $study]);
    }

    public function deleteSelectedStudies()
    {
        $ids = $this->request->getPost('ids');  // รับค่า IDs ของการศึกษา

        $studyModel = new StudyModel();

        // ลบข้อมูลที่เลือกทั้งหมด
        if ($studyModel->delete($ids)) {
            return $this->response->setJSON(['success' => true]);  // ส่งการตอบกลับว่า successful
        } else {
            return $this->response->setJSON(['success' => false]);  // ส่งการตอบกลับว่า failed
        }
    }


    // public function updateOpenSeats()
    // {
    //     $courseId = $this->request->getPost('course_id');
    //     $openSeats = $this->request->getPost('open_seats');

    //     // ตรวจสอบค่าที่ส่งมา
    //     if ($courseId && $openSeats) {
    //         $studyModel = new StudyModel();

    //         // อัปเดตจำนวนที่เปิดรับในฐานข้อมูล
    //         $studyModel->update($courseId, ['open_seats' => $openSeats]);

    //         return $this->response->setJSON(['success' => true]);
    //     } else {
    //         return $this->response->setJSON(['success' => false]);
    //     }
    // }
    public function fetchStudys()
    {
        $request = $this->request;
        $studyModel = new StudyModel();

        try {
            // รับค่า term_id จากการเลือกใน JavaScript (เช่นจาก hidden input)
            $term_id = $request->getVar('term_id');
            $search = esc($request->getVar('search')['value'] ?? '');
            $start = (int) $request->getVar('start', FILTER_SANITIZE_NUMBER_INT);
            $length = (int) $request->getVar('length', FILTER_SANITIZE_NUMBER_INT);

            $query = $studyModel->select('study.ID_Study, term_course.Term_name, course.Course_name, course.Price_DC, course.open, course.id as Course_id, COUNT(*) as count_students')
                ->join('course', 'study.ID_Courses = course.id', 'left')
                ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
                ->groupBy(['term_course.Term_name', 'course.Course_name', 'course.Price_DC', 'course.open']);

            // ตรวจสอบว่า term_id มีค่า ถ้ามีให้กรองตาม term_id
            if (!empty($term_id)) {
                $query->where('term_course.id', $term_id); // กรองข้อมูลตาม ID ของเทอม
            }

            // ตรวจสอบการค้นหาจากฟิลด์ search (ถ้ามี)
            if (!empty($search)) {
                $query->like('term_course.Term_name', $search); // ค้นหาจากชื่อเทอม
            }

            // ดึงข้อมูล
            $data = $query->limit($length, $start)->findAll();
            $totalStudents = 0;
            if (!empty($data)) {
                // คำนวณจำนวนผู้ลงทะเบียนทั้งหมด
                $totalStudents = array_sum(array_column($data, 'count_students'));
            }

            // ปรับปรุงส่วนของการสร้างข้อมูลใน DataTable
            return $this->response->setJSON([
                "draw" => intval($request->getVar('draw')),
                "recordsTotal" => $studyModel->countAll(),
                "recordsFiltered" => $query->countAllResults(false),
                "data" => array_map(function ($row) {
                    return [
                        '<input type="checkbox" class="select-row" value="' . $row['ID_Study'] . '">',
                        $row['ID_Study'],
                        $row['Term_name'],
                        $row['Course_name'],
                        $row['Price_DC'] . ' บาท',
                        $row['count_students'] . ' คน',
                        $row['open'] . ' คน',
                        ($row['open'] - $row['count_students']) . ' คน',
                        '<button class="btn-editOpen" data-id="' . $row['ID_Study'] . '" data-open="' . $row['open'] . '" data-course-id="' . $row['Course_id'] . '">แก้ไขจำนวนเปิดรับ</button>'
                            ,
                    ];
                }, $data)
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(["error" => $e->getMessage()]);
        }
    }

    public function getColumnName($index)
    {
        $columns = [
            'ID_Study',
            'Term_name',
            'Course_name',
            'Price_DC',
            'count_duplicate',
        ];

        return $columns[$index] ?? 'ID_Study';
    }

    public function deleteStudy($id)
    {
        try {
            $studyModel = new StudyModel();
            $result = $studyModel->delete($id);

            if ($result) {
                return $this->response->setJSON(['success' => true]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'ไม่สามารถลบข้อมูลได้']);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getStudentCount()
    {
        $studyModel = new StudyModel();
        $termId = $this->request->getPost('term_id');

        if (empty($termId)) {
            return $this->response->setJSON(['error' => 'term_id ไม่ถูกต้อง']);
        }

        $studentCount = $studyModel->getStudentCountByTerm($termId);

        log_message('debug', 'getStudentCount - Term ID: ' . $termId . ' - Count: ' . $studentCount);

        return $this->response->setJSON(['count' => (int) $studentCount]);
    }

    // ใน Controller ของคุณ (เช่น StudentPageController.php)
    public function getStudyCount()
    {
        $studyModel = new StudyModel();
        // ค้นหาจำนวน ID_Study ทั้งหมดในฐานข้อมูล
        $studyCount = $studyModel->countAll(); // ใช้ $studyModel แทน $this->$studyModel

        return $this->response->setJSON(['count' => $studyCount]);
    }

    public function updateOpenCount()
    {
        $request = $this->request;
        $courseModel = new CourseModel();
        $studyModel = new StudyModel();
    
        $courseId = $request->getPost('id');
        $newOpenCount = $request->getPost('open');
    
        // ตรวจสอบข้อมูลที่ได้รับ
        if (empty($courseId) || empty($newOpenCount)) {
            return $this->response->setJSON(['success' => false, 'error' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        }
    
        try {
            // ดึงข้อมูล count_students สำหรับ courseId ที่เลือก
            $query = $studyModel->select('COUNT(*) as count_students')
                ->join('course', 'study.ID_Courses = course.id', 'left')
                ->where('course.id', $courseId)
                ->groupBy('course.id')
                ->first();
    
            $countStudents = $query ? $query['count_students'] : 0; // ถ้าไม่พบข้อมูลให้กำหนดเป็น 0
    
            // ตรวจสอบว่า newOpenCount ต้องไม่น้อยกว่า count_students
            if ($newOpenCount < $countStudents) {
                return $this->response->setJSON(['success' => false, 'error' => 'จำนวนที่เปิดรับต้องไม่น้อยกว่าจำนวนผู้ลงทะเบียน']);
            }
    
            // อัพเดตจำนวน open ในคอร์ส
            $courseModel->updateCourse($courseId, ['open' => $newOpenCount]);
            return $this->response->setJSON(['success' => true]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    

    public function getStudentsByCourse()
    {
        $courseId = $this->request->getGet('course_id'); // รับ course_id จาก URL
        $termId = $this->request->getGet('term_id'); // รับ term_id จาก URL

        // ดึงข้อมูลนักเรียนที่ลงทะเบียนในคอร์สและเทอมที่กำหนด
        $studyModel = new \App\Models\StudyModel();
        $students = $studyModel->getStudyByTermAndCourseId($termId, $courseId);

        if ($students) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $students
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'error' => 'ไม่พบข้อมูลนักเรียน'
            ]);
        }
    }

    public function generateNames()
    {
        $ids = $this->request->getGet('ids');
    
        if (!$ids) {
            return "กรุณาเลือกนักเรียนก่อนพิมพ์รายชื่อ!";
        }
    
        $idsArray = explode(',', $ids);
    
        $studyModel = new StudyModel();
        $selectedStudent = $studyModel->select('term_course.Term_name, course.Course_name')
            ->join('course', 'study.ID_Courses = course.id', 'left')
            ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
            ->whereIn('study.ID_Study', $idsArray)
            ->first();
    
        if (!$selectedStudent) {
            return "ไม่พบข้อมูลนักเรียนที่เลือก!";
        }
    
        $studentsData = $studyModel->select('study.Firstname_S, study.Lastname_S, study.Phone_S, 
                                            study.Firstname_P, study.Lastname_P, study.Phone_P')
            ->join('course', 'study.ID_Courses = course.id', 'left')
            ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
            ->where('term_course.Term_name', $selectedStudent['Term_name'])
            ->where('course.Course_name', $selectedStudent['Course_name'])
            ->findAll();
    
        if (empty($studentsData)) {
            return "ไม่พบข้อมูลนักเรียนในเทอมและคอร์สที่เลือก!";
        }
    
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('THSarabunNew', '', 14);
    
        $pdf->Cell(0, 10, 'รายชื่อนักเรียนทั้งหมดของ ' . $selectedStudent['Term_name'] . ' ' . $selectedStudent['Course_name'], 0, 1, 'C');
        $pdf->Cell(0, 10, 'จำนวนทั้งหมด ' . count($studentsData) . ' คน', 0, 1, 'C');
        $pdf->Ln(5);
    
        $pdf->SetFont('THSarabunNew', 'B', 14);
        $pdf->Cell(10, 10, 'ลำดับ', 1, 0, 'C');
        $pdf->Cell(30, 10, 'ชื่อ', 1, 0, 'C');
        $pdf->Cell(30, 10, 'นามสกุล', 1, 0, 'C');
        $pdf->Cell(30, 10, 'โทรศัพท์', 1, 0, 'C');
        $pdf->Cell(30, 10, 'ชื่อผู้ปกครอง', 1, 0, 'C');
        $pdf->Cell(30, 10, 'เบอร์โทร', 1, 1, 'C');
    
        $pdf->SetFont('THSarabunNew', '', 14);
        $index = 1;
        foreach ($studentsData as $student) {
            $pdf->Cell(10, 10, $index++, 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Firstname_S'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Lastname_S'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Phone_S'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Firstname_P'] . ' ' . $student['Lastname_P'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Phone_P'], 1, 1, 'C');
        }
    
        $pdf->Output('รายชื่อนักเรียนทั้งหมด.pdf', 'D');
    }
}
