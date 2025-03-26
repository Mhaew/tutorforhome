<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>

    <!-- ลิงค์ไปที่ Bootstrap CSS สำหรับการใช้งานฟังก์ชันและการจัดรูปแบบ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- ลิงค์ไปที่ Google Fonts เพื่อใช้ฟอนต์ Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- เพิ่ม Font Awesome CDN หรือ Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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

        /* ซ่อนคอลัมน์ "วิธีการจ่ายเงิน" */
        table th:nth-child(7),
        table td:nth-child(7) {
            display: none;
        }

        .data-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .term-item {
            background-color: #f1f1f1;
            cursor: pointer;
            text-align: left;
            padding: 10px;
            border-radius: 5px;
        }

        .term-item:hover {
            background-color: #dcdcdc;
        }

        /* ปรับขนาดตาราง */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ddd;
            /* เพิ่มขอบให้กับตาราง */
        }

        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }

        /* ปรับขนาดปุ่ม */
        .btn-circle {
            width: 40px;
            height: 40px;
        }
    </style>

</head>

<body>
    <?php
    $session = session();
    if (!$session->get('id')) {
        return redirect()->to('/login');
    }
    ?>
    <?php
    $session = session();
    $user_status = $session->get('status');  // สมมติว่าคุณเก็บ status ของผู้ใช้ใน session
    ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= esc($error) ?>
        </div>
    <?php endif; ?>

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
            <li><a href="/studentpage" class="add-course">• รายงาน / พิมพ์รายชื่อนักเรียน</a></li>
            <li><a href="/employeepage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อผู้ใช้ระบบ</a></li>
            <li><a href="/userspage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อพนักงาน</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="container">
            <h2>พิมพ์รายชื่อนักเรียน</h2>
            <table id="studentTable" class="display">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"> <!-- คอลัมน์เช็คบ็อกซ์เลือกทั้งหมด -->
                        </th>
                        <th>ชื่อคอร์ส</th>
                        <th>ชื่อ-นามสกุล (นักเรียน)</th>
                        <th>เบอร์โทร (นักเรียน)</th>
                        <th>ชื่อ-นามสกุล (ผู้ปกครอง)</th>
                        <th>เบอร์ติดต่อ (ผู้ปกครอง)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($study as $row): ?>
                        <tr>
                            <td><input type="checkbox" class="checkbox" data-id="<?= esc($row['ID_Study']); ?>"></td>
                            <td><?= esc($row['Course_name']); ?></td>
                            <td><?= esc($row['Firstname_S']) . ' ' . esc($row['Lastname_S']); ?></td>
                            <td><?= esc($row['Phone_S']); ?></td>
                            <td><?= esc($row['Firstname_P']) . ' ' . esc($row['Lastname_P']); ?></td>
                            <td><?= esc($row['Phone_P']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button id="print-names" class="btn btn-primary">
                <i class="fas fa-print"></i> พิมพ์รายชื่อ
            </button>
            <?php if ($user_status === 'manager'): ?>
                <button id="deleteSelected" class="btn btn-danger">ลบที่เลือก</button>
            <?php endif; ?>
            <!-- ปุ่มลบสำหรับข้อมูลที่เลือก -->
        </div>
        <br>
        <a href="/studentpage">ย้อนกลับ</a>

        <!-- เพิ่ม JavaScript สำหรับ DataTable -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var table = $('#studentTable').DataTable({
                    "paging": true, // เปิดใช้การแบ่งหน้า
                    "searching": true, // เปิดใช้งานการค้นหา
                    "ordering": true, // เปิดใช้งานการเรียงลำดับ
                    "info": true // แสดงข้อมูลการแสดงหน้า
                });

                // การจัดการเหตุการณ์คลิกปุ่มลบสำหรับแต่ละแถว
                $(document).on('click', '.btn-delete', function() {
                    var studyId = $(this).data('id'); // ดึง ID_Study ที่ต้องการลบ
                    if (confirm('คุณแน่ใจว่าจะลบข้อมูลนี้?')) {
                        // ส่งคำขอลบไปยัง Controller
                        $.ajax({
                            url: '<?= base_url('deleteStudy'); ?>', // ปรับให้เป็น URL ของการลบ
                            type: 'POST',
                            data: {
                                id: studyId
                            },
                            success: function(response) {
                                // รีเฟรชหน้าเพื่อลบข้อมูลที่ถูกลบออกจากตาราง
                                if (response.success) {
                                    alert('ลบข้อมูลสำเร็จ');
                                    location.reload(); // รีเฟรชหน้าหลังจากลบสำเร็จ
                                } else {
                                    alert('ลบข้อมูลไม่สำเร็จ');
                                }
                            },
                            error: function() {
                                alert('เกิดข้อผิดพลาด');
                            }
                        });
                    }
                });

                $('#print-names').click(function() {
                    var selectedIds = [];

                    // ดึงค่าที่เลือกจาก checkbox ที่ถูกเลือก
                    $('input[type="checkbox"]:checked').each(function() {
                        var id = $(this).data('id'); // อ่านค่า data-id แทน value
                        if (id) {
                            selectedIds.push(id);
                        }
                    });

                    // ตรวจสอบว่ามีข้อมูลถูกเลือกหรือไม่
                    if (selectedIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'กรุณาเลือกข้อมูลก่อนพิมพ์รายชื่อ',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        return;
                    }

                    // สร้าง URL และเปลี่ยนหน้าไปพิมพ์รายชื่อ
                    var url = '/generateNames?ids=' + selectedIds.join(',');
                    window.location.href = url;
                });

                // การเลือกหรือยกเลิกเลือกทั้งหมด
                $('#select-all').on('click', function() {
                    var rows = table.rows({
                        'search': 'applied'
                    }).nodes();
                    $('input[type="checkbox"]', rows).prop('checked', this.checked);
                });

                // การลบข้อมูลที่เลือก
                $('#deleteSelected').on('click', function() {
                    var selectedIds = [];
                    // ดึง ID_Study ของรายการที่เลือก
                    $('input[type="checkbox"]:checked').each(function() {
                        selectedIds.push($(this).data('id'));
                    });

                    if (selectedIds.length === 0) {
                        alert('กรุณาเลือกข้อมูลที่ต้องการลบ');
                        return;
                    }

                    // ส่งคำขอลบข้อมูลที่เลือกไปยัง Controller
                    if (confirm('คุณแน่ใจว่าจะลบข้อมูลที่เลือก?')) {
                        $.ajax({
                            url: '<?= base_url('/deleteSelectedStudies'); ?>', // ปรับ URL สำหรับการลบหลายรายการ
                            type: 'POST',
                            data: {
                                ids: selectedIds
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert('ลบข้อมูลที่เลือกสำเร็จ');
                                    location.reload(); // รีเฟรชหน้าหลังจากลบสำเร็จ
                                } else {
                                    alert('ลบข้อมูลไม่สำเร็จ');
                                }
                            },
                            error: function() {
                                alert('เกิดข้อผิดพลาด');
                            }
                        });
                    }
                });
            });
        </script>

</body>

</html>