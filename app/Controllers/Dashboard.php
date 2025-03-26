<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DashboardModel;

class Dashboard extends Controller
{
    public function index()
    {
        echo view('index');
    }

    public function dashboard()
    {
        // เรียกใช้โมเดล
        $model = new DashboardModel();

        // ดึงข้อมูลเทอมทั้งหมด โดยกรองตาม user_id
        $session = session();
        $user_id = $session->get('id');
        $data['terms'] = $model->where('user_id', $user_id)->findAll(); // กรองตาม user_id

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if (empty($data['terms'])) {
            $data['message'] = 'ยังไม่มีข้อมูลเทอม';
        }

        echo view('dashboard', $data);
    }

    public function fetchTerms()
    {
        $dashboardModel = new DashboardModel();
        $query = $dashboardModel->findAll();

        $data = [];

        if (!empty($query)) {
            foreach ($query as $row) {
                $actions = '';

                // ปุ่มแก้ไข
                $actions .= '<button class="edit-btn btn btn-warning btn-sm" data-id="' . htmlspecialchars($row['id']) . '">แก้ไข</button>';
                // ปุ่มลบ
                $actions .= '<button class="delete-btn btn btn-danger btn-sm" data-id="' . htmlspecialchars($row['id']) . '">ลบ</button>';

                // เพิ่มข้อมูลในอาร์เรย์
                $data[] = [
                    'id' => $row['id'],
                    'term_detail' => htmlspecialchars($row['Term_name']), // ป้องกัน XSS
                    'actions' => $actions,
                ];
            }
        }

        return $this->response->setJSON(["data" => $data]);
    }

    public function updateTerms()
    {
        $Id = $this->request->getPost('id');
        $termName = $this->request->getPost('Term_name');

        // Debug ค่าที่ได้รับ
        log_message('debug', "Updating Term ID: $Id, New Name: $termName");

        $dashboardModel = new \App\Models\DashboardModel();
        $existingTerm = $dashboardModel->find($Id);

        if (!$existingTerm) {
            return $this->response->setJSON(['success' => false, 'message' => 'ไม่พบข้อมูล']);
        }

        // ตรวจสอบค่าซ้ำ
        $duplicateTerm = $dashboardModel->where('Term_name', $termName)
            ->where('id !=', $Id)
            ->first();

        if ($duplicateTerm) {
            return $this->response->setJSON(['success' => false, 'message' => 'เทอมซ้ำ']);
        }

        // ตรวจสอบว่า 'Term_name' ไม่ว่างเปล่า
        $data['Term_name'] = !empty($termName) ? $termName : $existingTerm['Term_name'];

        // อัปเดตข้อมูล
        $result = $dashboardModel->update($Id, $data);

        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'อัปเดตล้มเหลว']);
        }
    }

    public function deleteTerm($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบ ID']);
        }

        $model = new DashboardModel();

        if ($model->find($id)) {
            if ($model->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'ลบสำเร็จ']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบได้']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบข้อมูล']);
        }
    }

    public function getTermDetails($id = null)
    {
        if (!$id) {
            return $this->response->setStatusCode(400, 'ต้องระบุ ID');
        }

        $model = new DashboardModel();
        $term = $model->find($id);

        if ($term) {
            return $this->response->setJSON($term);
        } else {
            return $this->response->setStatusCode(404, 'ไม่พบข้อมูล');
        }
    }


    public function saveterm()
    {
        $model = new DashboardModel();
        $termListData = $this->request->getPost('term_list_data');
        $session = session();
        $user_id = $session->get('id'); // ดึง user_id จาก session

        // Validate and sanitize term list data
        if (!is_string($termListData)) {
            return $this->response->setContentType('application/json')->setJSON([
                'status' => 'error',
                'message' => 'Expected string but received ' . gettype($termListData)
            ]);
        }

        log_message('info', 'Received term_list_data: ' . $termListData);

        if ($termListData) {
            $terms = explode(',', $termListData);

            foreach ($terms as $term) {
                $term = trim($term);
                if (!empty($term)) {
                    try {
                        // ตรวจสอบว่าเทอมนี้มีอยู่ในฐานข้อมูลแล้วหรือไม่
                        $existingTerm = $model->where('Term_name', $term)
                            ->where('user_id', $user_id) // ตรวจสอบว่าเป็นของ user นี้
                            ->first();

                        if ($existingTerm) {
                            // ถ้ามีเทอมนี้อยู่แล้วในฐานข้อมูล
                            return $this->response->setContentType('application/json')->setJSON([
                                'status' => 'error',
                                'message' => "เทอม '$term' นี้มีอยู่แล้วในระบบ"
                            ]);
                        } else {
                            // ถ้าไม่มีเทอมนี้ในฐานข้อมูล ให้เพิ่มใหม่
                            $model->insert(['Term_name' => $term, 'user_id' => $user_id]);
                        }
                    } catch (\Exception $e) {
                        log_message('error', 'Failed to save term: ' . $e->getMessage());
                        return $this->response->setContentType('application/json')->setJSON([
                            'status' => 'error',
                            'message' => 'Failed to save term: ' . $e->getMessage()
                        ]);
                    }
                }
            }
            return $this->response->setContentType('application/json')->setJSON([
                'status' => 'success',
                'message' => 'Terms saved successfully.'
            ]);
        } else {
            return $this->response->setContentType('application/json')->setJSON([
                'status' => 'error',
                'message' => 'Invalid data format.'
            ]);
        }
    }
}
