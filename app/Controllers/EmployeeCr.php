<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class EmployeeCr extends Controller
{
    public function employeepage()
    {
        return view('employeepage');
    }

    public function fetchUsers()
    {
        $session = session();
        $userModel = new UserModel();
        $query = $userModel->findAll();
        $data = [];

        // รับค่า session
        $session_id = $session->get('id');
        $session_status = $session->get('status');

        // ถ้า session เป็น 'user' จะแสดงข้อมูลเฉพาะตัวเอง
        foreach ($query as $row) {
            $actions = '';  // กำหนดตัวแปรสำหรับปุ่ม actions
            $user_id = $row['id'];

            // เงื่อนไขกรณีที่ status เป็น 'user'
            if ($session_status === 'user') {
                // ถ้าเป็นข้อมูลของ user ที่ login เท่านั้นให้แสดง
                if ($user_id == $session_id) {
                    $actions = '<button class="btn btn-warning btn-edit" data-id="' . $user_id . '">แก้ไข</button>';
                    $data[] = [
                        'id' => $row['id'],
                        'username' => $row['username'],
                        'full_name' => $row['first_name'] . ' ' . $row['last_name'],
                        'email' => $row['email'],
                        'status' => $row['status'],
                        'actions' => $actions, // ส่งปุ่มที่ถูกต้องตามสิทธิ์
                    ];
                }
            } else {
                // สำหรับ 'admin' และ 'manager' สามารถดูข้อมูลของทุกคนได้
                if ($session_status === 'admin') {
                    if ($user_id == $session_id) {
                        $actions = '<button class="btn btn-warning btn-edit" data-id="' . $user_id . '">แก้ไข</button>' .
                            '<button class="btn btn-danger deleteBtn" data-id="' . $user_id . '">ลบ</button>';
                    } else {
                        $actions = '<button class="btn btn-warning btn-edit" data-id="' . $user_id . '">แก้ไขสถานะ</button>' .
                            '<button class="btn btn-danger deleteBtn" data-id="' . $user_id . '">ลบ</button>';
                    }
                } elseif ($session_status === 'manager') {
                    if ($user_id == $session_id) {
                        $actions = '<button class="btn btn-warning btn-edit" data-id="' . $user_id . '">แก้ไข</button>' .
                            '<button class="btn btn-danger deleteBtn" data-id="' . $user_id . '">ลบ</button>';
                    }
                }

                // เพิ่มข้อมูลสำหรับ 'admin' และ 'manager'
                $data[] = [
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'full_name' => $row['first_name'] . ' ' . $row['last_name'],
                    'email' => $row['email'],
                    'status' => $row['status'],
                    'actions' => $actions,
                ];
            }
        }

        return $this->response->setJSON(["data" => $data]);
    }

    public function updateUser()
    {
        // รับค่าข้อมูลจากฟอร์ม
        $userId = $this->request->getPost('user_id');
        $username = trim($this->request->getPost('username')); // ตัดช่องว่างหน้า-หลัง
        $firstName = trim($this->request->getPost('first_name'));
        $lastName = trim($this->request->getPost('last_name'));
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_new_password');
        $currentPassword = $this->request->getPost('current_password');
        $status = $this->request->getPost('status');

        // ดึงข้อมูลเดิมจากฐานข้อมูล
        $userModel = new \App\Models\UserModel();
        $existingUser = $userModel->find($userId);

        // ตรวจสอบว่าได้รับค่าชื่อผู้ใช้หรือไม่
        if (is_null($username) || $username === false) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ไม่ได้รับค่าชื่อผู้ใช้'
            ]);
        }

        // ตรวจสอบรหัสผ่านเดิมหากมีการเปลี่ยนแปลงรหัสผ่าน
        if (!empty($password)) {
            if (!password_verify($currentPassword, $existingUser['password'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'รหัสผ่านเดิมไม่ถูกต้อง'
                ]);
            }

            if ($password !== $confirmPassword) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'รหัสผ่านใหม่และยืนยันรหัสผ่านใหม่ไม่ตรงกัน'
                ]);
            }

            if (password_verify($password, $existingUser['password'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'รหัสผ่านใหม่ต้องไม่เหมือนรหัสเดิม'
                ]);
            }

            if (strlen($password) < 8 || strlen($password) > 16) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'รหัสผ่านต้องมีความยาวระหว่าง 8 ถึง 16 ตัวอักษร'
                ]);
            }

            // เข้ารหัสรหัสผ่านใหม่
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $passwordHash = null;
        }

        // เตรียมข้อมูลสำหรับการอัปเดต
        $data = [
            'username'   => !empty($username) ? $username : $existingUser['username'],
            'first_name' => !empty($firstName) ? $firstName : $existingUser['first_name'],
            'last_name'  => !empty($lastName) ? $lastName : $existingUser['last_name'],
            'email'      => !empty($email) ? $email : $existingUser['email'],
            'status'     => !empty($status) ? $status : $existingUser['status'],
        ];

        // อัปเดตรหัสผ่านถ้ามีการเปลี่ยนแปลง
        if ($passwordHash) {
            $data['password'] = $passwordHash;
        } else {
            $data['password'] = $existingUser['password'];
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $result = $userModel->update($userId, $data);

        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    public function addUser()
    {
        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // ตรวจสอบความยาวของ username และ password
        if (strlen($username) < 8 || strlen($username) > 16) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Username ต้องมีความยาวระหว่าง 8 ถึง 16 ตัวอักษร'
            ]);
        }

        if (strlen($password) < 8 || strlen($password) > 16) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Password ต้องมีความยาวระหว่าง 8 ถึง 16 ตัวอักษร'
            ]);
        }

        $data = [
            'username'   => $username,
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'status'     => $this->request->getPost('status'),
            'password'   => password_hash($password, PASSWORD_BCRYPT), // เข้ารหัสรหัสผ่าน
        ];

        // ตรวจสอบว่าข้อมูลครบถ้วน
        if (in_array(null, $data, true) || in_array('', $data, true)) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'ข้อมูลไม่ครบถ้วน'
            ]);
        }

        // ตรวจสอบว่า username หรือ email ซ้ำหรือไม่
        if ($userModel->where('username', $data['username'])->countAllResults() > 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Username นี้มีอยู่แล้ว'
            ]);
        }

        if ($userModel->where('email', $data['email'])->countAllResults() > 0) {
            return $this->response->setStatusCode(400)->setJSON([
                'status'  => 'error',
                'message' => 'Email นี้มีอยู่แล้ว'
            ]);
        }

        // เพิ่มข้อมูลลงฐานข้อมูล
        if ($userModel->insert($data)) {
            return $this->response->setStatusCode(201)->setJSON([
                'status'  => 'success',
                'message' => 'Employee added successfully'
            ]);
        } else {
            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Failed to add employee'
            ]);
        }
    }


    public function saveUser()
    {
        $model = new UserModel();
        $data = $this->request->getPost();

        if (!empty($data['id'])) {
            // หากมี id_member หมายถึงการอัปเดตข้อมูล
            $model->update($data['id'], $data);
            return $this->response->setJSON(['message' => 'อัปเดตข้อมูลสำเร็จ']);
        } else {
            // หากไม่มี id_member หมายถึงการเพิ่มข้อมูล
            $model->insert($data);
            return $this->response->setJSON(['message' => 'เพิ่มข้อมูลสำเร็จ']);
        }
    }




    public function getUser($id)
    {
        $model = new UserModel();
        $member = $model->find($id);

        if ($member) {
            return $this->response->setJSON(['status' => 'success', 'data' => $member]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่พบสมาชิก']);
        }
    }

    public function deleteUser($id)
    {
        $model = new UserModel();
        if ($model->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'ลบพนักงานสำเร็จ']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ไม่สามารถลบพนักงานได้']);
        }
    }

    public function getUserDetails($id)
    {
        $model = new UserModel();
        $user = $model->find($id);  // หาข้อมูลผู้ใช้จากฐานข้อมูลโดยใช้ ID

        if ($user) {
            return $this->response->setJSON($user);  // ส่งข้อมูลในรูปแบบ JSON กลับไป
        } else {
            return $this->response->setStatusCode(404, 'User not found');
        }
    }

    // public function updateUser()
    // {
    //     $session = session();
    //     $userModel = new UserModel();

    //     $data = $this->request->getPost();
    //     $userId = $data['user_id'];

    //     if ($session->get('id') == $userId || $session->get('status') == 'admin') {
    //         // ตรวจสอบสิทธิ์ในการแก้ไข
    //         $user = $userModel->find($userId);

    //         // อัปเดตข้อมูลสถานะ หรือข้อมูลอื่น ๆ
    //         if ($session->get('id') == $userId) {
    //             $user['first_name'] = $data['first_name'];
    //             $user['last_name'] = $data['last_name'];
    //             $user['email'] = $data['email'];
    //         }

    //         $user['status'] = $data['status'];

    //         $userModel->save($user);
    //         return $this->response->setJSON(['message' => 'อัปเดตข้อมูลสำเร็จ']);
    //     }

    //     return $this->response->setStatusCode(403)->setJSON(['message' => 'คุณไม่มีสิทธิ์ในการแก้ไข']);
    // }

    public function fetchUserById($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'ไม่พบข้อมูลพนักงาน'
            ]);
        }

        return $this->response->setJSON($user);
    }



}
