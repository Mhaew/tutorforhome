<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- เพิ่ม Font Awesome CDN หรือ Bootstrap Icons CDN -->
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
            /* ทำให้ตัวหนังสือหนา */
            color: white;
            /* ตัวหนังสือเป็นสีขาว */
            background-color: red;
            /* พื้นหลังเป็นสีแดง */
            padding: 10px;
            /* เพิ่มพื้นที่รอบๆ ข้อความ */
            text-align: left;
            /* จัดข้อความให้ตรงกลาง */
            margin-top: 10px;
            margin-bottom: 15px;
            /* เพิ่มระยะห่างจากรายการเมนู */
            border-radius: 5px;
            /* เพิ่มขอบมนเล็กน้อย */
        }
    </style>
</head>

<body>

    <?php
    $session = session();
    if (!$session->get('id')) {
        return redirect()->to('/login');
    }
    $user_status = $session->get('status');  // สมมติว่าคุณเก็บ status ของผู้ใช้ใน session
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-danger fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand text-white fw-bold fs-3" href="/dashboard">TuTor For Home</a>
            <div class="d-flex justify-content-end align-items-center">
                <!-- แสดงไอคอนผู้ใช้แทนข้อความยินดีต้อนรับ -->
                <span class="navbar-text me-3 text-white fw-bold fs-4">
                    <i class="bi bi-person-circle"></i> <!-- ไอคอนผู้ใช้ -->
                    <?= htmlspecialchars($session->get('username')) ?> <!-- แสดงชื่อผู้ใช้ -->
                </span>
                <!-- ปุ่มล็อกเอาท์กับไอคอนล็อกเอาท์ -->
                <a href="/logout" class="btn btn-danger fw-bold fs-5">
                    <i class="bi bi-box-arrow-right"></i> <!-- ไอคอนล็อกเอาท์ -->
                    ล็อกเอาท์
                </a>
            </div>
        </div>
    </nav>



    <div class="sidebar">

        <img src="/assets/image/tutorforhome.png" alt="Tutor for Home">
        <h3 class="menu-title">MENU</h3> <!-- เพิ่มข้อความ MENU -->
        <ul class="link-list">
            <?php if ($user_status === 'user'): ?>
                <li><a href="/dashboard">&nbsp;&nbsp;&nbsp;&nbsp;• Add Course / เพิ่มคอร์สเรียน</a></li>
                <li><a href="/studypage">&nbsp;&nbsp;&nbsp;&nbsp;• ข้อมูลสมัครเรียน</a></li>
                <li><a href="/paymentpage">&nbsp;&nbsp;&nbsp;&nbsp;• ยืนยันการชำระเงิน</a></li>
            <?php endif; ?>
            <li><a href="/billpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / พิมพ์ใบเสร็จ</a></li>
            <!-- <li><a href="/crouselishpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / คอร์สเรียน</a></li> -->
            <li><a href="/studentpage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / พิมพ์รายชื่อนักเรียน</a></li>
            <li><a href="/employeepage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อผู้ใช้ระบบ</a></li>
            <li><a href="/userspage" class="add-course">• รายงาน / รายชื่อพนักงาน</a></li>
        </ul>
    </div>

    <div class="content">

        <br>
        <h1>รายงาน / รายชื่อพนักงาน</h1>
        <!-- <p>กรุณาเลือกข้อมูลพนักงานจากตารางเพื่อพิมพ์รายชื่อ</p> -->
        <table id="membersTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อ</th>
                    <th>ตำแหน่ง</th>
                    <th>เบอร์โทร</th>
                    <th>อีเมล</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <?php if ($user_status === 'manager'): ?>
            <button class="btn btn-primary" id="addMemberBtn">เพิ่มพนักงาน</button>
        <?php endif; ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="memberModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่ม / แก้ไขพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="memberForm">
                        <input type="hidden" id="id_member">
                        <div class="mb-3">
                            <label class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="name_member" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ตำแหน่ง</label>
                            <input type="text" class="form-control" id="class" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทร</label>
                            <input type="text" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal แก้ไขสมาชิก -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMemberModalLabel">แก้ไขพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editMemberForm">
                        <div class="mb-3">
                            <label for="name_member" class="form-label">ชื่อ</label>
                            <input type="text" class="form-control" id="name_member" required>
                        </div>
                        <div class="mb-3">
                            <label for="class" class="form-label">ตำแหน่ง</label>
                            <input type="text" class="form-control" id="class" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทร</label>
                            <input type="text" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">ยืนยันการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // ฟังก์ชันแก้ไขข้อมูลสมาชิก


            var table = $('#membersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?= site_url('userspage/fetchUsers') ?>',
                    type: 'POST'
                },
                columns: [{
                        data: 'id_member', // คอลัมน์แรกจะเป็น ID
                        title: 'ID',
                        visible: false, // ซ่อน ID เพื่อไม่ให้แสดงในตาราง
                    },
                    {
                        data: 'name_member', // ชื่อ
                        title: 'ชื่อ'
                    },
                    {
                        data: 'class', // ตำแหน่ง
                        title: 'ตำแหน่ง'
                    },
                    {
                        data: 'phone', // เบอร์โทร
                        title: 'เบอร์โทร'
                    },
                    {
                        data: 'email', // อีเมล
                        title: 'อีเมล'
                    },
                    {
                        data: 'action',
                        title: '',
                        render: function(data, type, row) {
                            if ('<?= $user_status ?>' === 'manager') {
                                return `<button class="btn btn-warning" onclick="editUser(${row.id_member})">แก้ไข</button>`;
                            }
                            return '';
                        }
                    }
                ]
            });

            // ฟอร์มสำหรับการเพิ่มและแก้ไขข้อมูล
            $('#memberForm').on('submit', function(e) {
                e.preventDefault();

                var memberData = {
                    id_member: $('#id_member').val(),
                    name_member: $('#name_member').val(),
                    class: $('#class').val(),
                    phone: $('#phone').val(),
                    email: $('#email').val()
                };

                $.post('/userspage/saveUser', memberData, function(response) {
                    alert(response.message);
                    $('#membersTable').DataTable().ajax.reload(); // รีเฟรชข้อมูล
                    $('#memberModal').modal('hide'); // ปิด Modal
                });
            });

            // เปิด modal เพื่อเพิ่มข้อมูลใหม่
            $('#addMemberBtn').on('click', function() {
                $('#id_member').val('');
                $('#memberForm')[0].reset();
                $('#memberModal').modal('show');
            });
        });

        function editUser(id) {
            $.get(`/userspage/getUser/${id}`, function(response) {
                if (response.status === 'success') {
                    // ใส่ข้อมูลลงในฟอร์ม
                    $('#id_member').val(response.data.id_member);
                    $('#name_member').val(response.data.name_member);
                    $('#class').val(response.data.class);
                    $('#phone').val(response.data.phone);
                    $('#email').val(response.data.email); // ตรวจสอบอีเมลที่ได้รับ
                    // เปิด Modal แก้ไข
                    $('#memberModal').modal('show');
                } else {
                    alert('ไม่พบข้อมูล');
                }
            }).fail(function(xhr, status, error) {
                console.error("เกิดข้อผิดพลาดในการดึงข้อมูล:", error);
                alert("เกิดข้อผิดพลาดในการดึงข้อมูล");
            });
        }

        // ฟังก์ชันลบข้อมูล
        function deleteUser(id) {
            if (confirm("คุณต้องการลบสมาชิกนี้หรือไม่?")) {
                $.post("/userspage/deleteUser", {
                    id_member: id
                }, function(response) {
                    if (response.status === 'success') {
                        alert('ลบสมาชิกสำเร็จ');
                        $('#membersTable').DataTable().ajax.reload(); // รีเฟรชตาราง
                    } else {
                        alert('ไม่สามารถลบสมาชิกได้: ' + response.message);
                    }
                }).fail(function(xhr, status, error) {
                    console.error("เกิดข้อผิดพลาดในการลบสมาชิก:", error);
                    alert("เกิดข้อผิดพลาดในการลบสมาชิก");
                });
            }
        }
    </script>

</body>

</html>