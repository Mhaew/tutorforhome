<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>

    <!-- ลิงค์ไปที่ Bootstrap CSS สำหรับการใช้งานฟังก์ชันและการจัดรูปแบบ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- ลิงค์ไปที่ Google Fonts เพื่อใช้ฟอนต์ Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <!-- เพิ่ม Font Awesome CDN หรือ Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .btn-circle {
            border-radius: 50%;
            /* ทำให้ปุ่มเป็นรูปวงกลม */
            width: 35px;
            /* กำหนดขนาดของปุ่ม */
            height: 35px;
            /* กำหนดขนาดของปุ่ม */
            padding: 0;
            /* ลบ padding */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-circlee {
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }

        .btn-grey {
            background-color: #6c757d;
            /* สีเทา */
        }

        .btn-blue {
            background-color: #007bff;
            /* สีน้ำเงิน */
        }

        .btn-green {
            background-color: #28a745;
            /* สีเขียว */
        }

        .button-container {
            display: flex;
            align-items: center;
            /* จัดแนวปุ่มให้ตรงกลางในแนวตั้ง */
            gap: 10px;
            /* ระยะห่างระหว่างปุ่ม */
        }

        #confirm-payment {
            background-color: #4169E1;
            /* ขนาดตัวอักษร */
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
        <h3 class="menu-title">MENU</h3> <!-- เพิ่มข้อความ MENU -->
        <ul class="link-list">
            <?php if ($user_status === 'user'): ?>
                <li><a href="/dashboard">&nbsp;&nbsp;&nbsp;&nbsp;• Add Course / เพิ่มคอร์สเรียน</a></li>
                <li><a href="/studypage">&nbsp;&nbsp;&nbsp;&nbsp;• ข้อมูลสมัครเรียน</a></li>
                <li><a href="/paymentpage"class="add-course">• ยืนยันการชำระเงิน</a></li>
            <?php endif; ?>
            <li><a href="/billpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / พิมพ์ใบเสร็จ</a></li>
            <!-- <li><a href="/crouselishpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / คอร์สเรียน</a></li> -->
            <li><a href="/studentpage" >&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / พิมพ์รายชื่อนักเรียน</a></li>
            <li><a href="/employeepage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อผู้ใช้ระบบ</a></li>
            <li><a href="/userspage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อพนักงาน</a></li>
        </ul>
    </div>
    <div class="content">

        <br>
        <h1>ยืนยันการชำระเงิน</h1>
        <!-- <p> ข้อมูลผู้สมัครเรียน -->
        <p>
            <!-- <div class="content-main">
            <div class="data-container">
                <label for="terms">เลือกเทอม▾</label>
                <input type="hidden" id="termInput" name="termInput" value="">
                <?php if ($session->get('logged_in')) : ?>
                    <?php if (isset($terms)) : ?>
                        <?php

                        $loginTime = new DateTime($session->get('login_time'));
                        foreach ($terms as $term) :

                            $termDate = new DateTime($term['updated_at']);

                            if ($termDate > $loginTime) :
                        ?>
                                <div class="term-item" data-id="<?php echo htmlspecialchars($term['id']); ?>" onclick="selectTerm(this)">
                                    <?php echo htmlspecialchars($term['Term_name']); ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>
                <?php else : ?>

                <?php endif; ?>
            </div>
            <div class="data-container courses-container">
                <label for="courses">เลือกคอร์ส▾</label>
                <input type="hidden" id="courseInput" name="courseInput" value="">
                <div class="course-items">
                    <?php if ($session->get('logged_in')) : ?>
                        <?php if (isset($courses)) : ?>
                            <?php

                            $loginTime = new DateTime($session->get('login_time'));
                            foreach ($courses as $course) :

                                $courseDate = new DateTime($course['updated_at']);

                                if ($courseDate > $loginTime) :
                            ?>
                                    <div class="course-item" data-id="<?php echo htmlspecialchars($course['id']); ?>" onclick="selectCourse(this)">
                                        <?php echo htmlspecialchars($course['Course_name']); ?>
                                    </div>

                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p><?php echo htmlspecialchars($message); ?></p>
                        <?php endif; ?>
                    <?php else : ?>
                      
                    <?php endif; ?>
                </div>
            </div>
        </div> -->

        <table id="studyTable" class="display">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th> <!-- ✅ Checkbox เลือกทั้งหมด -->
                    <th>ID</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>รายการครอส</th>
                    <th>รายการเทอม</th>
                    <th>ยอดชำระเงิน</th>
                    <th>สถานะการชำระเงิน</th>
                    <th>วิธีการชำระเงิน</th>
                </tr>
            </thead>
        </table>

        <div class="button-container">
            <button id="confirm-payment" class="btn btn-success mb-3">เลือกวิธีการชำระเงิน</button>

            <!-- ปุ่มวงกลมสีต่างๆ -->
            <button class="btn-circlee btn-grey"></button> ยังไม่ได้เลือกวิธีชำระเงิน
            <button class="btn-circlee btn-blue"></button> ชำระแบบโอน
            <button class="btn-circlee btn-green"></button> ชำระแบบเงินสด
        </div>

        <!-- Modal เลือกวิธีการชำระเงิน -->
        <div class="modal" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paymentModalLabel">เลือกวิธีการชำระเงิน</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="paymentType">วิธีการชำระเงิน</label>
                            <select id="paymentType" class="form-control">
                                <option value="transfer">โอน</option> <!-- ตัวเลือก "โอน" -->
                                <option value="cash">เงินสด</option> <!-- ตัวเลือก "เงินสด" -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="button" id="confirmPaymentType" class="btn btn-primary">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </div>




    </div>
    <!-- ลิงค์ไปที่ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <!-- ฟังก์ชันซ่อนข้อมูลหลังจากล็อกเอาต์ -->
    <!-- <script>
        function hideDataAfterLogout() {
            const courseDetails = document.querySelector('.course-details');
            const courseList = document.querySelector('.course-list');

            if (courseDetails) {
                courseDetails.style.display = 'none';
            }

            if (courseList) {
                courseList.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.querySelector('.sidebar a[href="/logout"]');

            if (logoutLink) {
                logoutLink.addEventListener('click', function(event) {
                    event.preventDefault();
                    hideDataAfterLogout(); // ซ่อนข้อมูลเมื่อผู้ใช้คลิกลิงก์ล็อกเอาต์
                    window.location.href = '/logout'; // ทำการเปลี่ยนเส้นทางไปยังหน้าล็อกเอาต์
                });
            }
        });
    </script> -->

    <!-- ฟังก์ชันเลือกเทอม -->
    <!-- <script>
        function selectTerm(element) {
            const termId = element.getAttribute('data-id'); // Get ID from data-id
            const termInput = document.getElementById('ID_Terms');

            if (termId && termInput) {
                termInput.value = termId; // Update value in hidden input
            } else {
                console.error('Term ID or input element not found.');
            }
        }

        function selectCourse(element) {
            const courseId = element.setAttribute('data-id', course.id); // Get ID from data-id
            const courseInput = document.getElementById('ID_Courses');

            if (courseId && courseInput) {
                console.log(courseId); // Check the retrieved value
                courseInput.value = courseId; // Update value in hidden input
            } else {
                console.error('Course ID or input element not found.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const paymentAmountInput = document.getElementById('paymentAmount');
            const paymentOptions = document.getElementsByName('payment_option');

            paymentOptions.forEach(option => {
                option.addEventListener('change', function() {
                    if (this.value === 'full') {
                        paymentAmountInput.placeholder = 'ระบุยอดชำระเต็มจำนวน';
                    } else if (this.value === 'deposit') {
                        paymentAmountInput.placeholder = 'ระบุยอดมัดจำ';
                    }
                    paymentAmountInput.style.textAlign = 'center';
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            let selectedTerm = null;
            let selectedTermId = null;
            let selectedCourseItem = null;

            function selectTerm(termBox) {
                if (selectedTerm) {
                    selectedTerm.classList.remove('selected');
                }
                selectedTerm = termBox;
                selectedTerm.classList.add('selected');
                selectedTermId = selectedTerm.dataset.id;
                document.getElementById('termInput').value = selectedTermId;

                // ดึงข้อมูลคอร์สที่เกี่ยวข้อง
                fetchCourses(selectedTermId);
            }

            function selectCourse(courseItem) {
                if (selectedCourseItem) {
                    selectedCourseItem.classList.remove('selected');
                }
                selectedCourseItem = courseItem;
                selectedCourseItem.classList.add('selected');

                // Get the course ID and set it to hidden input
                const courseId = courseItem.getAttribute('data-id');
                document.getElementById('ID_Courses').value = courseId;

                // Log the selected course ID to check
                console.log('Selected Course ID: ', courseId);

                // Update the realPrice input field with the selected course's price
                const price = courseItem.dataset.price; // Retrieve price from data attribute
                document.getElementById('realPrice').value = price;

                // Update the Price_thai input field
                document.getElementById('Price_thai').value = numberToThaiText(parseFloat(price)) + ' บาทถ้วน';
            }

            async function fetchCourses(termId) {
                try {
                    const response = await fetch('/getcourses', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            termId
                        })
                    });
                    const courses = await response.json();

                    let coursesContainer = document.querySelector('.data-container:nth-child(2)');

                    // Clear existing courses but keep the label
                    coursesContainer.innerHTML = '<label for="courses">เลือกคอร์ส▾</label><input type="hidden" id="courseInput" name="courseInput" value="">';

                    courses.forEach(course => {
                        let courseItem = document.createElement('div');
                        courseItem.className = 'course-item';
                        courseItem.setAttribute('data-id', course.id);
                        courseItem.setAttribute('data-price', course.Price_DC); // Store price in data attribute
                        courseItem.textContent = course.Course_name;
                        courseItem.onclick = function() {
                            selectCourse(this);
                        };
                        coursesContainer.appendChild(courseItem);
                    });

                    // Show the courses container if it was hidden
                    coursesContainer.style.display = 'block';
                } catch (error) {
                    console.error('Error fetching courses:', error);
                }
            }

            function numberToThaiText(number) {
                const units = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน'];
                const digits = ['ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];

                let result = '';
                let unitIndex = 0;

                if (number === 0) return 'ศูนย์';

                while (number > 0) {
                    const currentDigit = number % 10;
                    if (currentDigit > 0) {
                        result = digits[currentDigit] + units[unitIndex] + result;
                    } else if (result.length > 0) {
                        result = digits[0] + result;
                    }

                    number = Math.floor(number / 10);
                    unitIndex++;
                }

                // Remove leading zeros or any additional 'ศูนย์' after the 'ล้าน'
                return result.replace(/ศูนย์+$/, '').replace(/(หนึ่งสิบ)/, 'สิบ');
            }

            document.querySelectorAll('.term-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectTerm(this);
                });
            });
        });
    </script> -->

    <script>
        $(document).ready(function() {
            var table = $('#studyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '/fetchStudys',
                    type: 'POST'
                },
                columns: [{
                        data: 0,
                        orderable: false,
                        searchable: false
                    }, // Checkbox
                    {
                        data: 1,
                        title: 'ID',
                        visible: false
                    }, // ID (ซ่อน)
                    {
                        data: 2,
                        title: 'ชื่อ-นามสกุล'
                    },
                    {
                        data: 3,
                        title: 'รายการเทอม'
                    },
                    {
                        data: 4,
                        title: 'รายการครอสเรียน'
                    },
                    {
                        data: 5,
                        title: 'ยอดชำระเงิน',
                        orderable: true
                    },
                    {
                        data: 6,
                        title: 'สถานะการชำระเงิน'
                    },
                    {
                        data: 7,
                        title: '',
                        orderable: true,
                        searchable: false
                    } // ปุ่มจัดการ
                ],
                order: [
                    [5, 'desc'] // การเรียงตามคอลัมน์ที่ 5 (ยอดชำระเงิน) จากมากไปน้อย
                ] // การเรียงข้อมูลเริ่มต้นที่คอลัมน์ 5 (ยอดชำระเงิน)
            });

            // ✅ กดปุ่ม "เลือกวิธีการชำระเงิน"
            $('#confirm-payment').on('click', function() {
                // เปิด Modal เพื่อให้เลือกวิธีการชำระเงิน
                $('#paymentModal').modal('show');
            });

            // ✅ กดปุ่ม "ยืนยัน" หลังจากเลือกวิธีการชำระเงิน
            $('#confirmPaymentType').on('click', function() {
                var paymentType = $('#paymentType').val(); // รับค่า Payment Type ที่เลือกจาก dropdown
                var selectedIds = [];

                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val()); // ดึงค่าของ ID ที่เลือกจาก checkbox
                });

                if (selectedIds.length === 0) {
                    alert('กรุณาเลือกข้อมูลก่อนยืนยัน');
                    return;
                }

                // ส่งข้อมูลวิธีการชำระเงินไปที่ Controller
                $.post('/confirm-payment', {
                    selectedIds: selectedIds,
                    paymentType: paymentType
                }, function(response) {
                    alert(response.message);
                    $('#paymentModal').modal('hide'); // ปิด Modal หลังยืนยัน
                    table.ajax.reload(); // รีโหลด DataTables หลังอัปเดต
                }, 'json');
            });
        });
    </script>

</body>

</html>