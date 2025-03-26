<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .btn-circle {
            border-radius: 50%;
            width: 35px;
            height: 35px;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-grey {
            background-color: #6c757d;
        }

        .btn-blue {
            background-color: #007bff;
        }

        .button-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #confirm-payment {
            background-color: #4169E1;
        }

        .menu-title {
            font-size: 20px;
            font-weight: bold;
            color: white;
            background-color: red;
            padding: 10px;
            text-align: left;
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <?php
    $session = session();
    // ตรวจสอบว่า session มีค่า user_id หรือไม่
    if (!session()->has('id')) {
        // ถ้าไม่มี session ให้ redirect หรือแสดงข้อความที่เหมาะสม
        return redirect()->to('/login');
    }

    $user_status = $session->get('status');
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-danger fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold fs-3" href="/dashboard">TuTor For Home</a>
            <div class="d-flex justify-content-end align-items-center">
                <span class="navbar-text me-3 text-white fw-bold fs-4">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($session->get('username')) ?>
                </span>
                <a href="/logout" class="btn btn-danger fw-bold fs-5">
                    <i class="bi bi-box-arrow-right"></i> ล็อกเอาท์
                </a>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <img src="/assets/image/tutorforhome.png" alt="Tutor for Home">
        <h3 class="menu-title">MENU</h3>
        <ul class="link-list">
            <?php if ($user_status === 'user'): ?>
                <li><a href="/dashboard">• Add Course / เพิ่มคอร์สเรียน</a></li>
                <li><a href="/studypage">&nbsp;&nbsp;&nbsp;&nbsp;• ข้อมูลสมัครเรียน</a></li>
                <li><a href="/paymentpage">&nbsp;&nbsp;&nbsp;&nbsp;• ยืนยันการชำระเงิน</a></li>
            <?php endif; ?>
            <li><a href="/billpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / พิมพ์ใบเสร็จ</a></li>
            <li><a href="/studentpage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / พิมพ์รายชื่อนักเรียน</a></li>
            <li><a href="/employeepage" class="add-course">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อผู้ใช้ระบบ</a></li>
            <li><a href="/userspage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อพนักงาน</a></li>

        </ul>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <br>
        <h1>รายงาน / รายชื่อผู้ใช้ระบบ</h1>
        <table id="usersTable" class="display table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be populated via JS -->
            </tbody>
        </table>
        <?php if ($user_status === 'admin'): ?>
            <button class="btn btn-primary" id="addMemberBtn">เพิ่มพนักงาน</button>
        <?php endif; ?>
    </div>

    <!-- Modal for Adding New Employee -->
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">เพิ่มพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMemberForm">
                        <div class="mb-3">
                            <label for="username_add" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username_add" required>
                        </div>
                        <div class="mb-3">
                            <label for="first_name_add" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name_add" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name_add" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name_add" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_add" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_add" required>
                        </div>
                        <div class="mb-3 " hidden>
                            <label for="status_add" class="form-label">Status</label>
                            <select class="form-control" id="status_add" required>
                                <option value="user">User</option> <!-- กำหนดให้เป็น "user" เท่านั้น -->
                            </select>
                        </div>
                        <!-- ช่องสำหรับรหัสผ่าน -->
                        <div class="mb-3">
                            <label for="password_add" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" id="password_add" required>
                        </div>
                        <!-- ช่องสำหรับยืนยันรหัสผ่าน -->
                        <div class="mb-3">
                            <label for="password_confirmation_add" class="form-label">ยืนยันรหัสผ่าน</label>
                            <input type="password" class="form-control" id="password_confirmation_add" required>
                        </div>
                        <button type="submit" class="btn btn-primary">เพิ่มพนักงาน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Employee -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMemberModalLabel">แก้ไขข้อมูลพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMemberForm">
                        <input type="hidden" id="user_id_edit" name="user_id">
                        <div class="mb-3">
                            <label for="username_edit" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username_edit" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="first_name_edit" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name_edit" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name_edit" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name_edit" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_edit" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email_edit" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">รหัสผ่านเดิม</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="mb-3">
                            <label for="confirm_new_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password">
                        </div>

                        <div class="mb-3">
                            <label for="status_edit" class="form-label">Status</label>
                            <select class="form-control" id="status_edit" name="status" disabled>
                                <option value="user">พนักงาน</option>
                                <option value="manager">ผู้จัดการ</option>
                                <option value="admin" hidden>แอดมิน</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">อัปเดตข้อมูลพนักงาน</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#usersTable').DataTable({
                "ajax": {
                    "url": "/employeepage/fetchUsers", // Ensure this URL is correct
                    "dataSrc": "data"
                },
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "username"
                    },
                    {
                        "data": "full_name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "id", // ใช้ `id` เพื่อเช็คการแสดงปุ่มแก้ไข
                        "render": function(data, type, row) {
                            // ดึง user_id ที่เก็บใน session (สมมติว่าเก็บในตัวแปร session_user_id)
                            var session_user_id = <?php echo json_encode(session()->get('id')); ?>;
                            console.log(session_user_id); // ตรวจสอบค่าจาก PHP

                            // ถ้า data ตรงกับ session_user_id ให้แสดงปุ่มแก้ไข
                            if (data == session_user_id) {
                                return '<button class="btn btn-warning btn-edit" data-id="' + data + '">แก้ไข</button>' +
                                    '<button class="btn btn-danger deleteBtn" data-id="' + data + '">ลบ</button>';
                            } else if ('<?= $user_status ?>' === 'manager') {
                                // ถ้าไม่ใช่เจ้าของไอดี ให้แสดงปุ่มลบ
                                return '<button class="btn btn-danger deleteBtn" data-id="' + data + '">ลบ</button>';
                            } else if ('<?= $user_status ?>' === 'admin') {
                                return '<button class="btn btn-warning btn-edit" data-id="' + data + '">แก้ไข</button>'
                            } else {
                                return '';
                            }
                        }
                    }

                ]
            });

            // Open Add Employee Modal
            // Open Add Employee Modal
            $('#addMemberBtn').click(function() {
                $('#addMemberModal').modal('show');
            });

            $('#addMemberForm').submit(function(e) {
                e.preventDefault();

                var username = $('#username_add').val();
                var password = $('#password_add').val();
                var confirmPassword = $('#password_confirmation_add').val();

                // ตรวจสอบความยาวของ username
                if (username.length < 8 || username.length > 16) {
                    alert('Username ต้องมีความยาวระหว่าง 8 ถึง 16 ตัวอักษร');
                    return;
                }

                // ตรวจสอบความยาวของ password
                if (password.length < 8 || password.length > 16) {
                    alert('Password ต้องมีความยาวระหว่าง 8 ถึง 16 ตัวอักษร');
                    return;
                }

                // ตรวจสอบรหัสผ่านและยืนยันรหัสผ่านให้ตรงกัน
                if (password !== confirmPassword) {
                    alert('รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน');
                    return;
                }

                // ตรวจสอบข้อมูลที่กรอก
                var data = {
                    username: username,
                    first_name: $('#first_name_add').val(),
                    last_name: $('#last_name_add').val(),
                    email: $('#email_add').val(),
                    status: $('#status_add').val(),
                    password: password // ส่งรหัสผ่านที่กรอก
                };

                console.log(data); // แสดงข้อมูลใน console เพื่อตรวจสอบ

                // ส่งข้อมูลไปที่เซิร์ฟเวอร์
                $.ajax({
                    url: '/employeepage/addUser',
                    method: 'POST',
                    data: data,
                    success: function(response) {
                        alert('เพิ่มพนักงานสำเร็จ');
                        $('#addMemberModal').modal('hide');
                        table.ajax.reload(); // รีเฟรช DataTable
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // ดูข้อความผิดพลาดจากเซิร์ฟเวอร์
                        alert('ชื่อผู้ใช้หรืออีเมล ถูกใช้ไปแล้ว');
                    }
                });
            });


            $('#usersTable').on('click', '.deleteBtn', function() {
                var userId = $(this).data('id'); // ดึง user_id จากปุ่ม
                if (confirm('คุณต้องการลบข้อมูลพนักงานนี้ใช่หรือไม่?')) {
                    $.ajax({
                        url: '/employeepage/deleteUser/' + userId, // URL สำหรับการลบ
                        method: 'DELETE',
                        success: function(response) {
                            alert('ลบพนักงานสำเร็จ');
                            table.ajax.reload(); // รีเฟรช DataTable
                        },
                        error: function(xhr, status, error) {
                            alert('เกิดข้อผิดพลาดในการลบข้อมูลพนักงาน');
                        }
                    });
                }
            });

            // Handle Edit Employee Form Submission
            // เปิด modal สำหรับแก้ไขเมื่อคลิกปุ่ม "แก้ไข"
            $('#usersTable').on('click', '.btn-edit', function() {
                var userId = $(this).data('id'); // ดึง user_id จากปุ่ม
                $.ajax({
                    url: '/employeepage/getUserDetails/' + userId,
                    method: 'GET',
                    success: function(response) {
                        // แสดงข้อมูลในฟอร์มแก้ไข
                        $('#user_id_edit').val(response.id);
                        $('#username_edit').val(response.username);
                        $('#first_name_edit').val(response.first_name);
                        $('#last_name_edit').val(response.last_name);
                        $('#email_edit').val(response.email);

                        // ตรวจสอบสิทธิ์ผู้ใช้ในการแก้ไขข้อมูล
                        var sessionUserId = <?php echo json_encode(session()->get('id')); ?>;
                        var sessionUserStatus = '<?php echo json_encode(session()->get('status')); ?>';

                        // ถ้าผู้ใช้กำลังแก้ไขข้อมูลของตัวเอง
                        if (sessionUserId == response.id) {
                            $('#username_edit').prop('disabled', false);
                            $('#first_name_edit').prop('disabled', false);
                            $('#last_name_edit').prop('disabled', false);
                            $('#email_edit').prop('disabled', false);
                            $('#current_password').prop('disabled', false);
                            $('#new_password').prop('disabled', false);
                            $('#confirm_new_password').prop('disabled', false);

                            if (sessionUserStatus !== 'admin') {
                                $('#status_edit').prop('disabled', true); // ปิดการแก้ไขสถานะสำหรับผู้ใช้ธรรมดา
                            } else {
                                $('#status_edit').prop('disabled', false); // เปิดการแก้ไขสถานะสำหรับ admin
                            }
                        } else {
                            // ถ้าเป็นข้อมูลของคนอื่น
                            $('#username_edit').prop('disabled', true);
                            $('#first_name_edit').prop('disabled', true);
                            $('#last_name_edit').prop('disabled', true);
                            $('#email_edit').prop('disabled', true);
                            $('#current_password').prop('disabled', true);
                            $('#new_password').prop('disabled', true);
                            $('#confirm_new_password').prop('disabled', true);

                            // ในส่วนการตรวจสอบสิทธิ์การแก้ไข status
                            if (sessionUserStatus === 'admin') {
                                $('#status_edit').prop('disabled', true); // เปิดการแก้ไขสถานะสำหรับ admin
                            } else {
                                $('#status_edit').prop('disabled', false); // ปิดการแก้ไขสถานะสำหรับผู้ใช้ทั่วไป
                            }

                        }

                        // เปิด modal แก้ไขข้อมูล
                        $('#editMemberModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        alert('ไม่สามารถดึงข้อมูลได้');
                    }
                });
            });

            $('#editMemberForm').on('submit', function(e) {
                e.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

                var formData = $(this).serialize(); // ดึงข้อมูลทั้งหมดจากฟอร์ม

                $.ajax({
                    url: '/employeepage/updateUser', // URL สำหรับการอัปเดตข้อมูล
                    method: 'POST',
                    data: formData, // ส่งข้อมูลทั้งหมดไปยังเซิร์ฟเวอร์
                    success: function(response) {
                        if (response.success) {
                            alert('ข้อมูลถูกอัปเดตเรียบร้อย');
                            $('#editMemberModal').modal('hide');
                            location.reload(); // รีเฟรชหน้าเพื่อล้างค่า
                        } else {
                            alert(response.message); // แสดงข้อความจากเซิร์ฟเวอร์
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                    }
                });
            });



        });
    </script>

    <!-- External JS Libraries -->
    <!-- ใส่การโหลด jQuery ก่อนที่สคริปต์อื่น ๆ -->


</body>

</html>