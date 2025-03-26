<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Logout extends Controller
{
    public function logout()
    {
        $session = session();
        $session->destroy(); // ทำลาย session

        // ถ้าคุณต้องการล้าง cookies อื่นๆ เพิ่มเติม
        // setcookie('cookie_name', '', time() - 3600);

        // กลับไปยังหน้าหลักหรือหน้าล็อกอิน
        return redirect()->to('/login'); // เปลี่ยน URL ตามที่ต้องการ
    }
}
