<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Page</title>

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
        // หากผู้ใช้ไม่ได้ล็อกอินอยู่ ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
        return redirect()->to('/login');
    }
    ?>
    <?php
    $session = session();
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
        <!-- เพิ่มแถบ "MENU" ที่นี่ -->
        <h3 class="menu-title">MENU</h3> <!-- เพิ่มข้อความ MENU -->

        <?php
        $session = session();
        $user_status = $session->get('status');  // สมมติว่าคุณเก็บ status ของผู้ใช้ใน session
        ?>
        <ul class="link-list">
            <?php if ($user_status === 'user'): ?>
                <li><a href="/dashboard">&nbsp;&nbsp;&nbsp;&nbsp;• Add Course / เพิ่มคอร์สเรียน</a></li>
                <li><a href="/studypage">&nbsp;&nbsp;&nbsp;&nbsp;• ข้อมูลสมัครเรียน</a></li>
                <li><a href="/paymentpage">&nbsp;&nbsp;&nbsp;&nbsp;• ยืนยันการชำระเงิน</a></li>
            <?php endif; ?>
            <li><a href="/billpage" class="add-course">• ค้นหารายชื่อ / พิมพ์ใบเสร็จ</a></li>
            <!-- <li><a href="/crouselishpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / คอร์สเรียน</a></li> -->
            <li><a href="/studentpage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / พิมพ์รายชื่อนักเรียน</a></li>
            <li><a href="/employeepage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อผู้ใช้ระบบ</a></li>
            <li><a href="/userspage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อพนักงาน</a></li>
        </ul>
    </div>
    <div class="content">

        <br>
        <h1>รายงานการชำระเงิน</h1>
        <!-- <p>กรุณาเลือกข้อมูลนักเรียนจากตารางเพื่อพิมพ์รายชื่อ</p> -->

        <div class="data-container">
            <h2>เลือกเทอม▾</h2>
            <input type="hidden" id="termInput" name="termInput" value="">

            <?php if ($session->get('logged_in')) : ?>
                <?php if (isset($terms)) : ?>
                    <?php foreach ($terms as $term) : ?>
                        <div class="term-item" data-id="<?php echo htmlspecialchars($term['id']); ?>" onclick="selectTerm(this)">
                            <?php echo htmlspecialchars($term['Term_name']); ?>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p><?php echo htmlspecialchars($message); ?></p>
                <?php endif; ?>
            <?php else : ?>
                <!-- สามารถใส่ข้อความหรือปล่อยให้ว่างได้ -->
            <?php endif; ?>
        </div>
        <br>

        <h1 id="student-count">จำนวนนักเรียนทั้งหมด .... คน </h1>
        <a href="javascript:void(0);" id="print-bill" class="btn btn-primary">
            <i class="fas fa-print"></i> พิมพ์ใบเสร็จ
        </a>
        <input type="hidden" id="courseIdInput" />
        <div>
            <br>
            <table id="studyTable" class="display">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th> <!-- ✅ Checkbox เลือกทั้งหมด -->
                        <th>ID</th>
                        <th>รายการเทอม</th>
                        <th>รายการครอสเรียน</th>
                        <th>ยอดชำระเงิน</th>
                        <th>จำนวนนักเรียน</th>
                        <th>ยอดรวม</th>
                        <th>ค้างชำระ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ข้อมูลใน <tbody> จะถูกเติมเข้าไปโดย DataTable -->
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" id="courseIdInput" />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        function updateStudentCount(termId) {
            console.log("เลือกเทอม ID:", termId);

            if (!termId) {
                // หากยังไม่ได้เลือกเทอม จะแสดงจำนวน ID_Study
                $.ajax({
                    url: '/studentpage/getStudyCount', // สมมติว่า endpoint นี้คืนค่าจำนวน ID_Study
                    method: 'POST',
                    success: function(response) {
                        console.log("Response:", response);
                        if (response.count !== undefined) {
                            $('#student-count').text('จำนวนนักเรียนทั้งหมด ' + response.count + ' คน');
                        } else {
                            $('#student-count').text('ไม่พบข้อมูลนักเรียน');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("เกิดข้อผิดพลาด:", error);
                    }
                });
            } else {
                // หากเลือกเทอมแล้ว จะแสดงจำนวนนักเรียนตามเทอม
                $.ajax({
                    url: '/studentpage/getStudentCount',
                    method: 'POST',
                    data: {
                        term_id: termId
                    },
                    success: function(response) {
                        console.log("Response:", response);
                        if (response.count !== undefined) {
                            $('#student-count').text('จำนวนนักเรียนทั้งหมด ' + response.count + ' คน');
                        } else {
                            $('#student-count').text('ไม่พบข้อมูลนักเรียน');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("เกิดข้อผิดพลาด:", error);
                    }
                });
            }
        }

        function selectTerm(element) {
            const termId = element.getAttribute('data-id');
            const termInput = document.getElementById('termInput');

            console.log("เลือกเทอม:", termId);

            if (termId && termInput) {
                termInput.value = termId;
                reloadTable(termId);
                updateStudentCount(termId); // เรียกใช้ฟังก์ชันเพื่ออัปเดตจำนวนนักเรียน
            } else {
                console.error('Term ID หรือ input ไม่ถูกต้อง');
            }
        }

        function reloadTable(termId) {
            $('#studyTable').DataTable().ajax.reload();
        }


        $(document).ready(function() {
            var table = $('#studyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/billpage/fetchStudys',
                    type: 'POST',
                    data: function(d) {
                        d.term_id = $('#termInput').val(); // ส่ง term_id ไปด้วย
                    }
                },
                columns: [{
                        data: 0,
                        orderable: false,
                        searchable: false,
                        visible: false,
                        className: 'text-center',
                    },
                    {
                        data: 1,
                        title: 'ID',
                        visible: false,
                        className: 'text-center',
                    },
                    {
                        data: 2,
                        title: 'รายการเทอม',
                        visible: false,
                        className: 'text-center',
                    },
                    {
                        data: 3,
                        title: 'รายการครอสเรียน',
                        className: 'text-center',
                    },
                    {
                        data: 4,
                        title: 'ยอดชำระเงิน',
                        orderable: true,
                        className: 'text-center',
                    },
                    {
                        data: 5,
                        title: 'จำนวนนักเรียน'
                    },
                    {
                        data: 6,
                        title: 'ยอดรวม'
                    },
                    {
                        data: 7,
                        title: 'ค้างชำระ'
                    }
                ]
            });

            // เมื่อมีการเปลี่ยนแปลงค่า term_id (เช่น เลือกจาก Dropdown)
            $('#termInput').on('change', function() {
                table.ajax.reload(); // รีโหลดข้อมูลใหม่เมื่อเลือก term ใหม่
            });

            var initialTermId = $('#termInput').val();
            if (initialTermId) {
                updateStudentCount(initialTermId); // อัปเดตจำนวนเมื่อลงหน้าเว็บ
                $('#studyTable').show(); // แสดงตารางเมื่อมีการเลือกเทอม
            } else {
                updateStudentCount(null); // หากไม่มีการเลือกเทอม ให้แสดงจำนวน ID_Study
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#print-bill').click(function() {
                // รับค่า term_id ที่เลือกจาก input หรือ dropdown
                var termId = $('#termInput').val();

                if (!termId) {
                    alert('กรุณาเลือกเทอมก่อน');
                    return;
                }

                // สร้าง URL สำหรับการไปยังหน้า printStudent พร้อมส่ง term_id
                var url = '<?= base_url("/printBill") ?>?term_id=' + termId;

                // เปลี่ยนเส้นทางไปยัง URL นี้
                window.location.href = url;
            });
        });
    </script>
</body>

</html>