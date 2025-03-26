<?php

namespace App\Controllers;

use App\Models\DashboardModel;
use App\Models\OrdersModel;
use CodeIgniter\Controller;
use App\Models\CourseModel;

class Orders extends Controller
{
    public function saveOrders()
    {
        $data = [
            'Status_Price' => $this->request->getPost('Status_Price'),
            'Total' => $this->request->getPost('Total'),
            'Discount' => $this->request->getPost('Discount'),
            'Price_thai' => $this->request->getPost('Price_thai'),
            'balance' => $this->request->getPost('balance')
        ];

        // Check for missing or empty data
        if (array_filter($data, function ($value) {
            return $value === null || trim($value) === '';
        })) {
            return $this->response->setJSON(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        }

        $model = new OrdersModel();

        if ($model->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'ข้อมูลถูกบันทึกแล้ว!']);
        } else {
            // Log the error message
            log_message('error', 'Database insertion failed: ' . json_encode($model->errors()));

            return $this->response->setJSON(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . json_encode($model->errors())]);
        }
    }

}
