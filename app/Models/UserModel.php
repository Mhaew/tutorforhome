<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'first_name', 'last_name', 'status'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getEmployees()
    {
        // ฟังก์ชันนี้จะดึงข้อมูลพนักงานที่มี status = 'employee'
        return $this->where('status', 'employee')->findAll();
    }

    // ใน UserModel
    public function deleteUser($id)
    {
        return $this->delete($id); // หรือหากใช้ query แบบ custom ก็อาจจะต้องใช้คำสั่ง DELETE ที่เหมาะสม
    }

    public function updateUser($userId, $data)
    {
        // ดึงข้อมูลเดิมจากฐานข้อมูล
        $existingUser = $this->find($userId);  // ค้นหาข้อมูลเดิมจากฐานข้อมูล
    
        // ตรวจสอบว่ามีการส่งค่า 'status' หรือ 'password' มาหรือไม่
        if (!isset($data['status'])) {
            // ถ้าไม่มีการส่งค่า 'status' มาจากฟอร์ม, ให้ใช้ค่าที่มีอยู่ในฐานข้อมูล
            $data['status'] = $existingUser['status'];
        }
    
        if (!isset($data['password']) || empty($data['password'])) {
            // ถ้าไม่มีการส่งค่า 'password' หรือเป็นค่าว่าง, ให้ใช้ค่าที่มีอยู่ในฐานข้อมูล
            $data['password'] = $existingUser['password'];
        }
    
        // อัปเดตข้อมูลทั้งหมด
        return $this->update($userId, $data);
    }
    
    
    

}
