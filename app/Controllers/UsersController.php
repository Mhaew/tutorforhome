<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MemberModel;

class UsersController extends Controller
{
    public function userspage()
    {
        return view('userspage');
    }

    public function fetchUsers()
    {
        $memberModel = new MemberModel();
        $query = $memberModel->findAll();
        $data = [];

        foreach ($query as $row) {
            $data[] = [
                'id_member' => $row['id_member'],
                'name_member' => $row['name_member'],
                'class' => $row['class'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'action' => '
                    <button class="btn btn-warning" onclick="editUser(' . $row['id_member'] . ')">แก้ไข</button>
                    <button class="btn btn-danger" onclick="deleteUser(' . $row['id_member'] . ')">ลบ</button>
                '
            ];
        }

        return $this->response->setJSON(["data" => $data]);
    }

    public function saveUser()
    {
        $model = new MemberModel();
        $data = $this->request->getPost();

        // ตรวจสอบว่าอีเมลที่ได้รับมาถูกต้อง
        if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'อีเมลไม่ถูกต้อง']);
        }

        if (isset($data['phone']) && (strlen($data['phone']) !== 10)) {
            return $this->response->setJSON(['success' => false, 'message' => 'เบอร์โทรต้องมี 10 ตัว']);
        }

        if (!empty($data['id_member'])) {
            // หากมี id_member หมายถึงการอัปเดตข้อมูล
            $model->update($data['id_member'], $data);
            return $this->response->setJSON(['message' => 'อัปเดตข้อมูลสำเร็จ']);
        } else {
            // หากไม่มี id_member หมายถึงการเพิ่มข้อมูล
            $model->insert($data);
            return $this->response->setJSON(['message' => 'เพิ่มข้อมูลสำเร็จ']);
        }
    }

    public function getUser($id)
    {
        $model = new MemberModel();
        $user = $model->find($id);

        if ($user) {
            // ตรวจสอบก่อนส่งข้อมูล
            log_message('debug', 'ข้อมูลที่ส่ง: ' . json_encode($user)); // เพิ่มการ log ข้อมูล

            return $this->response->setJSON(['status' => 'success', 'data' => $user]);
        } else {
            return $this->response->setJSON(['status' => 'error']);
        }
    }





    public function deleteUser()
    {
        $id_member = $this->request->getPost('id_member');
        if ($id_member) {
            $model = new MemberModel();
            // ตรวจสอบว่า id_member มีค่าและตรงกับข้อมูลในฐานข้อมูลหรือไม่
            $member = $model->find($id_member);
            if ($member) {
                if ($model->delete($id_member)) {
                    return $this->response->setJSON(['status' => 'success']);
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบสมาชิกได้']);
                }
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบสมาชิกที่มี id_member นี้']);
            }
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบ ID ของสมาชิก']);
        }
    }


    public function addUser()
    {
        $model = new MemberModel();
        $data = $this->request->getPost();
        if ($model->insert($data)) {
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error']);
    }
}
