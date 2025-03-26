<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Register extends Controller
{

    public function index()
    {
        helper(['form']); // Load form helper
        $data = [];
        echo view('register', $data); // Render the register view
    }

    public function save()
    {
        helper(['form']); // Load form helper

        // Define validation rules
        $rules = [
            // 'name' => 'required|min_length[3]|max_length[20]',
            // 'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            // 'password' => 'required|min_length[6]|max_length[200]',
            // 'confpassword' => 'matches[password]'
            'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
            'email' => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'confpassword' => 'required|matches[password]',
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
        ];
        
       
        // Validate input against rules
        if ($this->validate($rules)) {
            // Validation passed, save data to database
            $model = new UserModel(); // Create instance of UserModel


            $data = [
                'username' => $this->request->getVar('username'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'first_name'=> $this->request->getVar('first_name'),
                'last_name'=> $this->request->getVar('last_name'),
            ];
            
            // Save data using the model
            $model->save($data);


            // Redirect to login page upon successful registration
            return redirect()->to('/login');
        } else {
            // Validation failed, retrieve validation errors
            $data['validation'] = $this->validator;
            echo view('register', $data); // Render register view with validation errors
        }
    }

}
