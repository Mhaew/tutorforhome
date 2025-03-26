<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class Login extends Controller
{
    public function index()
    {
        $session = session();
        if ($session->get('logged_in')) {
            return redirect()->to('/dashboard'); // Redirect if already logged in
        }

        helper(['form']);
        return view('login');
    }


    public function auth()
    {
        $session = session();
        $model = new UserModel();
        $email = esc($this->request->getVar('email')); // ป้องกัน SQL Injection
        $password = $this->request->getVar('password');

        $data = $model->where('email', $email)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_password = password_verify($password, $pass);

            if ($verify_password) {
                // สร้าง session ให้ผู้ใช้
                $session->set([
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'status' => $data['status'], // ใช้ 'role' แทน 'status' เพื่อความเข้าใจที่ดีขึ้น
                    'logged_in' => true,
                    'login_time' => date('Y-m-d H:i:s') // เวลาที่ล็อกอิน
                ]);

                // ตรวจสอบบทบาทเพื่อกำหนดเส้นทาง
                if ($data['status'] === 'admin' || $data['status'] === 'manager') {
                    return redirect()->to('/employeepage'); // admin และ manager ไปที่ /employeepage
                } else {
                    return redirect()->to('/dashboard'); // ผู้ใช้ทั่วไปไปที่ /dashboard
                }
            } else {
                $session->setFlashdata('msg', 'Wrong password!!');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email not found!!');
            return redirect()->to('/login');
        }
    }

    public function displayTerms()
    {
        $session = session();
        $model = new DashboardModel();
        $loginTime = $session->get('login_time');
        $terms = $model->where('created_at >', $loginTime)->findAll();

        $data = [
            'terms' => $terms,
            'message' => count($terms) > 0 ? 'New terms available' : 'No new terms available'
        ];

        return view('terms_view', $data);
    }



    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
