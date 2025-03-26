<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class ResetPassword extends Controller
{
    public function index($token)
    {
        $model = new UserModel();
        $user = $model->where('reset_token', $token)->first();

        if (!$user) {
            // ถ้าไม่พบ token ในฐานข้อมูล
            return redirect()->to('/forgot_password')->with('error', 'Invalid token.');
        }

        // ส่ง token ไปยัง view สำหรับ reset password
        return view('reset_password', ['token' => $token]);
    }

    public function submit()
    {
        helper(['form', 'url']);

        $rules = [
            'token' => 'required',
            'password' => 'required|min_length[6]',
            'confpassword' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return view('reset_password', [
                'validation' => $this->validator,
                'token' => $this->request->getVar('token')
            ]);
        } else {
            $token = $this->request->getVar('token');
            $password = $this->request->getVar('password');

            $model = new UserModel();
            $user = $model->where('reset_token', $token)->first();

            if (!$user) {
                // ถ้าไม่พบ token ในฐานข้อมูล
                return redirect()->to('/forgot_password')->with('error', 'Invalid token.');
            }

            // อัพเดตรหัสผ่านใหม่
            $model->update($user['id'], ['password' => password_hash($password, PASSWORD_DEFAULT), 'reset_token' => null]);

            // ลบ token ในฐานข้อมูลหลังจากใช้งาน
            $model->update($user['id'], ['reset_token' => null]);

            return redirect()->to('/login')->with('success', 'Password has been reset successfully. Please login.');
        }
    }
}
