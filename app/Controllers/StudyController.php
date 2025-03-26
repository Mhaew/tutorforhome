<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\StudyModel;
use TCPDF;

class StudyController extends Controller
{
    public function generateNames()
    {
        $ids = $this->request->getGet('ids');

        // ถ้าไม่ได้ส่งค่า IDs มา
        if (!$ids) {
            return "กรุณาเลือกนักเรียนก่อนพิมพ์รายชื่อ!";
        }

        // แปลงค่า IDs เป็น array
        $idsArray = explode(',', $ids);

        // ดึงข้อมูลของนักเรียนที่ถูกเลือก
        $studyModel = new StudyModel();
        $selectedStudent = $studyModel->select('term_course.Term_name, course.Course_name')
            ->join('course', 'study.ID_Courses = course.id', 'left')
            ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
            ->whereIn('study.ID_Study', $idsArray)
            ->first();

        // ถ้าไม่พบข้อมูล
        if (!$selectedStudent) {
            return "ไม่พบข้อมูลนักเรียนที่เลือก!";
        }

        // ดึงรายชื่อนักเรียนทั้งหมดที่อยู่ในเทอมและคอร์สเดียวกัน
        $studentsData = $studyModel->select('study.ID_Study, study.Firstname_S, study.Lastname_S, study.Phone_S, 
                                            study.Firstname_P, study.Lastname_P, study.Phone_P, 
                                            course.Course_name, term_course.Term_name')
            ->join('course', 'study.ID_Courses = course.id', 'left')
            ->join('term_course', 'study.ID_Terms = term_course.id', 'left')
            ->where('term_course.Term_name', $selectedStudent['Term_name'])
            ->where('course.Course_name', $selectedStudent['Course_name'])
            ->findAll();

        // ถ้าไม่มีข้อมูลนักเรียนในเทอมและคอร์สเดียวกัน
        if (empty($studentsData)) {
            return "ไม่พบข้อมูลนักเรียนในเทอมและคอร์สที่เลือก!";
        }

        // ใช้ TCPDF เพื่อสร้างไฟล์ PDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('THSarabunNew', '', 14);

        // ใส่หัวข้อ
        $pdf->Cell(0, 10, 'รายชื่อนักเรียนทั้งหมดของ ' . $selectedStudent['Term_name'] . ' ' . $selectedStudent['Course_name'], 0, 1, 'C');
        $pdf->Cell(0, 10, 'จำนวนทั้งหมด ' . count($studentsData) . ' คน', 0, 1, 'C');
        $pdf->Ln(5);

        // กำหนดหัวตาราง
        $pdf->SetFont('THSarabunNew', 'B', 14);
        $pdf->Cell(10, 10, 'ID', 1, 0, 'C');
        $pdf->Cell(30, 10, 'ชื่อ', 1, 0, 'C');
        $pdf->Cell(30, 10, 'นามสกุล', 1, 0, 'C');
        $pdf->Cell(30, 10, 'โทรศัพท์', 1, 0, 'C');
        $pdf->Cell(30, 10, 'ชื่อผู้ปกครอง', 1, 0, 'C');
        $pdf->Cell(30, 10, 'เบอร์โทร', 1, 1, 'C');

        // ใส่ข้อมูลนักเรียนทีละแถว
        $pdf->SetFont('THSarabunNew', '', 14);
        foreach ($studentsData as $student) {
            $pdf->Cell(10, 10, $student['ID_Study'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Firstname_S'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Lastname_S'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Phone_S'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Firstname_P'] . ' ' . $student['Lastname_P'], 1, 0, 'C');
            $pdf->Cell(30, 10, $student['Phone_P'], 1, 1, 'C'); // ใช้ 1,1 เพื่อขึ้นบรรทัดใหม่
        }

        // ส่งออกไฟล์ PDF
        $pdf->Output('รายชื่อนักเรียนทั้งหมด.pdf', 'D'); // 'D' หมายถึงดาวน์โหลดไฟล์
    }
}
