<?php

namespace App\Controllers;

use App\Models\CourseModel;
use CodeIgniter\Controller;

class CourseController extends Controller
{
    public function saveCourse()
    {
        $session = session();
        $userId = $session->get('id');

        // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนบันทึกข้อมูล']);
        }

        // รับข้อมูล JSON จากคำขอ
        $inputData = $this->request->getJSON(true);

        if (!$inputData || !is_array($inputData)) {
            return $this->response->setJSON(['success' => false, 'message' => 'รูปแบบข้อมูลไม่ถูกต้อง']);
        }

        $model = new CourseModel();
        $insertedData = [];
        $updatedData = [];
        $errors = [];

        foreach ($inputData as $termData) {
            $termId = $termData['termId'] ?? null;
            $courses = $termData['courses'] ?? [];

            if (!$termId || !is_array($courses) || empty($courses)) {
                $errors[] = "ข้อมูลเทอมไม่ครบถ้วน (termId: {$termId})";
                continue;
            }

            foreach ($courses as $course) {
                $courseName = $course['courseName'] ?? null;
                $price = $course['price'] ?? null;

                if (!$courseName || !$price) {
                    $errors[] = "ข้อมูลคอร์สไม่ครบถ้วนในเทอม {$termId}";
                    continue;
                }

                // ตรวจสอบว่าในเทอมนี้มีคอร์สนี้อยู่หรือไม่
                $existingCourse = $model->where('id_term', $termId)
                    ->where('Course_name', $courseName)
                    ->first();

                $data = [
                    'id_term' => $termId,
                    'Course_name' => $courseName,
                    'Price_DC' => $price,
                    'id_user' => $userId,
                ];

                if ($existingCourse) {
                    // ถ้ามีคอร์สแล้ว ให้ทำการอัปเดตราคา
                    $model->update($existingCourse['id'], $data);
                    $updatedData[] = $existingCourse['id'];
                } else {
                    // ถ้าไม่มีคอร์สนี้ ให้ทำการเพิ่มข้อมูลใหม่
                    try {
                        $insertID = $model->insert($data);
                        if ($insertID) {
                            $insertedData[] = $insertID;
                        } else {
                            $errors[] = "เกิดข้อผิดพลาดในการบันทึกคอร์ส {$courseName} ในเทอม {$termId}";
                        }
                    } catch (\Exception $e) {
                        $errors[] = "ข้อผิดพลาด: " . $e->getMessage();
                    }
                }
            }
        }

        // สร้างผลลัพธ์เพื่อตอบกลับ
        if (empty($errors)) {
            return $this->response->setJSON(['success' => true, 'message' => 'บันทึกข้อมูลสำเร็จ', 'data' => ['inserted' => $insertedData, 'updated' => $updatedData]]);
        }

        return $this->response->setJSON([
            'success' => !empty($insertedData) || !empty($updatedData),
            'message' => empty($insertedData) && empty($updatedData) ? 'เกิดข้อผิดพลาดทั้งหมด' : 'บันทึกบางส่วนสำเร็จ',
            'data' => ['inserted' => $insertedData, 'updated' => $updatedData],
            'errors' => $errors,
        ]);
    }



    public function getCoursesByTerms()
    {
        $termIds = $this->request->getJSON(true);

        if (!$termIds || !is_array($termIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'รูปแบบข้อมูลไม่ถูกต้อง']);
        }

        $courseModel = new CourseModel();
        $courses = $courseModel->whereIn('id_term', $termIds)->findAll();

        return $this->response->setJSON(['success' => true, 'data' => $courses]);
    }
}
