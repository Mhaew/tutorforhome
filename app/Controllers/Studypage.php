<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use App\Models\StudyModel;
use CodeIgniter\Controller;
use App\Models\CourseModel;
use App\Models\paymantmodel;

class Studypage extends Controller
{

    public function studypage()
    {
        $session = session();
        $user_id = $session->get('id');

        if (!$user_id) {
            return redirect()->to('/login');
        }

        $dashboardModel = new DashboardModel();
        $courseModel = new CourseModel();

        $data['terms'] = $dashboardModel->findAll(); 
        $data['courses'] = $courseModel->where('id_user', $user_id)->findAll();
        $data['isLoggedIn'] = $session->get('isLoggedIn') ?? false;

        if (empty($data['terms'])) {
            $data['terms_message'] = 'ยังไม่มีข้อมูลเทอม';
        }

        if (empty($data['courses'])) {
            $data['courses_message'] = 'ยังไม่มีข้อมูลคอร์สเรียน';
        }

        return view('studypage', $data);
    }

    public function saveStudy()
    {
        // Validation rules
        $validation = \Config\Services::validation();

        $validation->setRules([
            'ID_Terms' => 'required',
            'ID_Courses' => 'required',
            'Title_name' => 'required|string|max_length[255]',
            'Firstname_S' => 'required|string|max_length[255]',
            'Lastname_S' => 'required|string|max_length[255]',
            'Phone_S' => 'required|numeric|max_length[10]',
            'Firstname_P' => 'required|string|max_length[255]',
            'Lastname_P' => 'required|string|max_length[255]',
            'Phone_P' => 'required|numeric|max_length[10]',
            'Status_Price' => 'required',
            'Total' => 'required|numeric',
            'Discount' => 'permit_empty|numeric',
            'Price_thai' => 'required|string',
            'balance' => 'required|numeric',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $validation->getErrors()
            ]);
        }

        // Data to insert
        $data = [
            'ID_Terms' => $this->request->getPost('ID_Terms'),
            'ID_Courses' => $this->request->getPost('ID_Courses'),
            'Title_name' => $this->request->getPost('Title_name'),
            'Firstname_S' => $this->request->getPost('Firstname_S'),
            'Lastname_S' => $this->request->getPost('Lastname_S'),
            'Phone_S' => $this->request->getPost('Phone_S'),
            'Firstname_P' => $this->request->getPost('Firstname_P'),
            'Lastname_P' => $this->request->getPost('Lastname_P'),
            'Phone_P' => $this->request->getPost('Phone_P'),
            'Status_Price' => $this->request->getPost('Status_Price'),
            'Total' => $this->request->getPost('Total'),
            'Discount' => $this->request->getPost('Discount'),
            'Price_thai' => $this->request->getPost('Price_thai'),
            'balance' => $this->request->getPost('balance'),
        ];

        // Using a transaction (if needed)
        $db = \Config\Database::connect();
        $db->transStart();

        $model = new StudyModel();

        if ($model->insert($data)) {
            $db->transComplete();

            if ($db->transStatus() === FALSE) {
                return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'ข้อมูลถูกบันทึกแล้ว!']);
        } else {
            $db->transRollback();
            log_message('error', 'Database insertion failed: ' . json_encode($model->errors()));
            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . json_encode($model->errors())]);
        }
    }



    public function getCourses()
    {
        $termId = $this->request->getPost('termId');
        $courseModel = new CourseModel();

        // Validate termId
        $term = $courseModel->find($termId);
        if (!$term) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูลเทอม']);
        }

        // Fetch courses for the selected term
        $courses = $courseModel->where('id_term', $termId)->findAll();

        return $this->response->setJSON($courses);
    }
}
