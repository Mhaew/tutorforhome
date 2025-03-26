<?php

namespace App\Controllers;

use App\Models\StudyModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use CodeIgniter\Controller;
use App\Models\CourseModel;
use App\Models\paymantmodel;
use App\Models\DashboardModel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Dompdf;

class ReceiptController extends Controller
{
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
