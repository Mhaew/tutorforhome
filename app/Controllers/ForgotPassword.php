<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ForgotPassword extends Controller
{
    public function index()
    {
        return view('forgot_password');
    }

    public function submit()
    {
        helper(['form', 'url']);

        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return view('forgot_password', [
                'validation' => $this->validator
            ]);
        } else {
            $email = $this->request->getVar('email');
            $model = new UserModel();

            // ตรวจสอบว่ามีอีเมลในระบบหรือไม่
            $user = $model->where('email', $email)->first();

            if ($user) {
                // ดำเนินการส่งลิงก์สำหรับการตั้งรหัสผ่านใหม่
                // สำหรับตัวอย่างนี้จะแสดงข้อความว่าลิงก์ถูกส่งแล้ว
                return redirect()->to('/forgot_password')->with('success', 'Password reset link has been sent to your email.');
            } else {
                return redirect()->to('/forgot_password')->with('error', 'Email address not found.');
            }
        }
    }
}
