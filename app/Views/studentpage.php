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

        <br>
        <h1>รายงานข้อมูลการลงทะเบียน</h1>
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
        <a href="javascript:void(0);" id="print-students" class="btn btn-primary">
            <i class="fas fa-print"></i> รายชื่อนักเรียน
        </a>
        <input type="hidden" id="courseIdInput" />
        <div>
            <br>
            <table id="studyTable" class="display">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th> <!-- ✅ Checkbox เลือกทั้งหมด -->
                        <th>รหัสการศึกษา</th> <!-- คอลัมน์สำหรับ ID_Study -->
                        <th>เทอม</th> <!-- คอลัมน์สำหรับ Term_name -->
                        <th>รายชื่อคอร์ส</th> <!-- คอลัมน์สำหรับ Course_name -->
                        <th>ราคาคอร์ส</th> <!-- คอลัมน์สำหรับ Price_DC -->
                        <th>จำนวนลงทะเบียน</th> <!-- คอลัมน์สำหรับ count_students -->
                        <th>จำนวนที่เปิดรับ</th> <!-- คอลัมน์สำหรับ open -->
                        <th>จำนวนที่เหลือ</th> <!-- คอลัมน์สำหรับจำนวนที่เหลือ -->
                        <th></th> <!-- คอลัมน์สำหรับปุ่มจัดการ -->
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal สำหรับแก้ไขจำนวนเปิดรับ -->
    <div class="modal" id="editOpenModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขจำนวนเปิดรับ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editOpenForm">
                        <div class="form-group">
                            <label for="openCountInput">จำนวนที่เปิดรับ</label>
                            <input type="number" class="form-control" id="openCountInput" required>
                        </div>
                        <input type="hidden" id="studyIdInput">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="button" id="saveOpenCount" class="btn btn-primary">บันทึก</button>
                </div>
            </div>
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
            var userStatus = '<?= $user_status ?>'; // รับค่าจาก PHP

            var table = $('#studyTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: '/studentpage/fetchStudys',
                    type: 'POST',
                    data: function(d) {
                        d.term_id = $('#termInput').val();
                    },
                    error: function(xhr, status, error) {
                        console.error("Ajax Error:", error);
                        alert("เกิดข้อผิดพลาดในการโหลดข้อมูล");
                    }
                },
                columns: [{
                        data: 0,
                        orderable: false,
                        searchable: false,
                        visible: false
                    },
                    {
                        data: 1,
                        title: 'รหัสการศึกษา',
                        visible: false
                    },
                    {
                        data: 2,
                        title: 'เทอม',
                        visible: false
                    },
                    {
                        data: 3,
                        title: 'รายชื่อคอร์ส',
                        visible: true,
                        className: 'text-center',
                    },
                    {
                        data: 4,
                        title: 'ราคาคอร์ส',
                        visible: true,
                        className: 'text-center',
                    },
                    {
                        data: 5,
                        title: 'จำนวนลงทะเบียน',
                        visible: true,
                        className: 'text-center',
                    },
                    {
                        data: 6,
                        title: 'จำนวนที่เปิดรับ',
                        visible: true,
                        className: 'text-center',
                    },
                    {
                        data: 7,
                        title: 'จำนวนที่เหลือ',
                        visible: true,
                        className: 'text-center',
                    },
                    {
                        data: 8,
                        title: 'จัดการ',
                        visible: true
                    }
                ]
            });

            // ซ่อน 3 คอลัมน์สุดท้าย หากสถานะเป็น 'user'
            if (userStatus !== 'manager') { // ซ่อนคอลัมน์ 'จำนวนที่เหลือ'
                table.column(8).visible(false); // ซ่อนคอลัมน์ 'จัดการ'
            }
        });



        $('#print-names').click(function() {
            var selectedIds = [];
            $('#studyTable input[type="checkbox"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('กรุณาเลือกข้อมูลก่อนพิมพ์รายชื่อ');
                return;
            }

            var url = '/studentpage/generateNames?ids=' + selectedIds.join(',');
            window.location.href = url;
        });

        $('#select-all').on('click', function() {
            var rows = table.rows({
                'search': 'applied'
            }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });

        var initialTermId = $('#termInput').val();
        if (initialTermId) {
            updateStudentCount(initialTermId); // อัปเดตจำนวนเมื่อลงหน้าเว็บ
            $('#studyTable').show(); // แสดงตารางเมื่อมีการเลือกเทอม
        } else {
            updateStudentCount(null); // หากไม่มีการเลือกเทอม ให้แสดงจำนวน ID_Study
        }

        $('#termInput').on('change', function() {
            var termId = $(this).val();
            if (termId) {
                updateStudentCount(termId);
                $('#studyTable').show(); // แสดงตารางเมื่อเปลี่ยนเทอม
            } else {
                $('#studyTable').hide(); // ซ่อนตารางถ้าไม่มีเทอมที่เลือก
                updateStudentCount(null); // อัปเดตเป็นจำนวน ID_Study
            }
        });

        // เพิ่มฟังก์ชันการคลิกปุ่ม "ลบ"
        $('#studyTable').on('click', '.btn-delete', function() {
            var studyId = $(this).data('id');

            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')) {
                $.ajax({
                    url: '/studentpage/deleteStudy/' + studyId, // ปรับ URL ตามเส้นทางที่คุณใช้
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            alert('ลบข้อมูลสำเร็จ');
                            location.reload(); // รีเฟรชข้อมูลใน DataTable
                        } else {
                            alert('ลบข้อมูลล้มเหลว');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting data:", error);
                        alert("เกิดข้อผิดพลาดในการลบข้อมูล");
                    }
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // เมื่อคลิกปุ่มแก้ไขจำนวนที่เปิดรับ
            $('body').on('click', '.btn-editOpen', function() {
                var studyId = $(this).data('id'); // ดึง ID ของการศึกษาจาก data-id
                var openCount = $(this).data('open'); // ดึงจำนวนเปิดรับจาก data-open
                var courseId = $(this).data('course-id'); // ดึง Course_id จาก data-course-id

                // ตรวจสอบว่า courseId ถูกดึงมาได้หรือไม่
                console.log('courseId:', courseId); // ตรวจสอบค่า courseId

                // เปิดฟอร์ม (หรือ modal) เพื่อให้ผู้ใช้แก้ไขจำนวนที่เปิดรับ
                $('#editOpenModal').modal('show');
                $('#studyIdInput').val(studyId);
                $('#openCountInput').val(openCount);
                $('#courseIdInput').val(courseId); // ตั้งค่า courseId ใน input ที่เกี่ยวข้อง
            });

            // เมื่อคลิกปุ่มบันทึกในฟอร์มแก้ไข
            $('#saveOpenCount').on('click', function() {
                var courseId = $('#courseIdInput').val(); // ตรวจสอบค่า courseId ใน input
                console.log('courseId:', courseId); // ตรวจสอบค่า courseId ที่ได้จาก input

                var newOpenCount = $('#openCountInput').val();
                console.log('newOpenCount:', newOpenCount); // ตรวจสอบค่า open

                if (!courseId || !newOpenCount) {
                    alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }

                $.ajax({
                    url: '/updateOpenCount', // ตรวจสอบ URL ของการอัพเดต
                    type: 'POST',
                    data: {
                        id: courseId, // ส่ง courseId ที่ได้รับจาก input
                        open: newOpenCount // ส่งจำนวนเปิดรับใหม่
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('ข้อมูลอัพเดตสำเร็จ');
                            location.reload();
                        } else {
                            alert('เกิดข้อผิดพลาดในการอัพเดตข้อมูล: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText); // ตรวจสอบ error ที่ได้รับจาก server
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#print-students').click(function() {
                // รับค่า term_id ที่เลือกจาก input หรือ dropdown
                var termId = $('#termInput').val();

                if (!termId) {
                    alert('กรุณาเลือกเทอมก่อน');
                    return;
                }

                // สร้าง URL สำหรับการไปยังหน้า printStudent พร้อมส่ง term_id
                var url = '<?= base_url("/printStudent") ?>?term_id=' + termId;

                // เปลี่ยนเส้นทางไปยัง URL นี้
                window.location.href = url;
            });
        });
    </script>
</body>

</html>