<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Manager</title>

    <!-- ลิงค์ไปที่ Bootstrap CSS สำหรับการใช้งานฟังก์ชันและการจัดรูปแบบ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- ลิงค์ไปที่ Google Fonts เพื่อใช้ฟอนต์ Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- เพิ่ม Font Awesome CDN หรือ Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <style>
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

        .course-list {
            background-color: #FBCBCB;
            padding: 20px;
            border-radius: 20px;
            width: 30vh;
            /* ปรับความกว้าง*/
            height: 70vh;
            /* ปรับความสูง
    box-sizing: border-box;
    max-width: 100%; /* กำหนดขนาดสูงสุดของความกว้างไม่เกิน 100% ของพาเรนต์ */
            max-height: 100vh;
            /* กำหนดขนาดสูงสุดของความสูงไม่เกิน 100% ของวิวพอร์ต */

        }

        .data-container {
            margin: 0 auto;
            /* จัดตำแหน่งให้คอนเทนเนอร์อยู่กลาง */
            max-height: 300px;
            /* กำหนดความสูงสูงสุดที่ต้องการ */
            overflow-y: auto;
            /* แสดงแถบเลื่อนในแนวตั้งเมื่อข้อมูลเกิน */
        }

        .term-item {
            background-color: #f1f1f1;
            /* พื้นหลังสีอ่อน */
            /* มุมโค้งมน */
            cursor: pointer;
            /* ให้แสดงเป็นคลิกได้ */
            text-align: left;
            /* จัดข้อความให้เริ่มจากซ้าย */
        }

        /* เพิ่มเมื่อ hover */
        .term-item:hover {
            background-color: #dcdcdc;
        }

        .courses-list {
            padding: 20px;
        }

        .courses-list h1 {
            font-size: 18px;
            margin-bottom: 15px;
            text-align: center;
        }

        .course-item {
            background-color: #e2e2e2;
            /* พื้นหลังสีคอร์ส */
            padding: 12px;
            /* ระยะห่างภายใน */
            margin: 8px 0;
            /* ระยะห่างระหว่างรายการ */
            border-radius: 4px;
            /* มุมโค้งมน */
            cursor: pointer;
            /* แสดงเป็นคลิกได้ */
            transition: background-color 0.3s ease;
            /* การเปลี่ยนแปลงสีเมื่อ hover */
        }

        .course-item:hover {
            background-color: #c4c4c4;
            /* เปลี่ยนสีเมื่อ hover */
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

    <!-- <div class="search-container">

        <input type="text" id="searchBox" placeholder="ค้นหา...">
    </div> -->

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
                <li><a href="/dashboard" class="add-course">• Add Course / เพิ่มคอร์สเรียน</a></li>
                <li><a href="/studypage">&nbsp;&nbsp;&nbsp;&nbsp;• ข้อมูลสมัครเรียน</a></li>
                <li><a href="/paymentpage">&nbsp;&nbsp;&nbsp;&nbsp;• ยืนยันการชำระเงิน</a></li>
            <?php endif; ?>
            <li><a href="/billpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / พิมพ์ใบเสร็จ</a></li>
            <!-- <li><a href="/crouselishpage">&nbsp;&nbsp;&nbsp;&nbsp;• ค้นหารายชื่อ / คอร์สเรียน</a></li> -->
            <li><a href="/studentpage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / พิมพ์รายชื่อนักเรียน</a></li>
            <li><a href="/employeepage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อผู้ใช้ระบบ</a></li>
            <li><a href="/userspage">&nbsp;&nbsp;&nbsp;&nbsp;• รายงาน / รายชื่อพนักงาน</a></li>
        </ul>
    </div>

    <div class="content">

        <div class="content-header">
            <a href="/dashboard" class="inactive"><b>เพิ่มเทอม</b></a>
            <a href="/dashboard_term" class="">เพิ่มคอร์สเรียน</a>
        </div>

        <div class="content-main">
            <div class="course-list">
                <h3><strong>เทอม 1 / 2567</strong></h3>▼
                <hr>
                <p>&nbsp;คอร์สเรียน ป.1</p>
                <p>&nbsp;คอร์สเรียน ป.2</p>
                <p>&nbsp;คอร์สเรียน ป.3</p>
                <p>&nbsp;คอร์สเรียน ป.4</p>
                <p>&nbsp;คอร์สเรียน ป.6</p>
                <p>&nbsp;คอร์สเรียน ม.1</p>
                <p>&nbsp;คอร์สเรียน ม.2</p>
                <p>&nbsp;คอร์สเรียน ม.3</p>

                <br>
                <strong>
                    <h3>เทอม 2 / 2567</h3>▼
                </strong>
                <hr>
                <p>&nbsp;คอร์สเรียน ป.1</p>
                <p>&nbsp;คอร์สเรียน ป.2</p>
                <p>&nbsp;คอร์สเรียน ป.3</p>
                <p>&nbsp;คอร์สเรียน ป.4</p>
                <p>&nbsp;คอร์สเรียน ป.6</p>
                <p>&nbsp;คอร์สเรียน ม.1</p>
                <p>&nbsp;คอร์สเรียน ม.2</p>
                <p>&nbsp;คอร์สเรียน ม.3</p>
            </div>


            <div class="course-details">

                <div class="input-group">
                    <div class="data-container">
                        <h1>เลือกเทอม▾</h1>
                        <!-- <label for="terms"></label> -->
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
                    </div><br><br><br><br><br><br><br>
                    <div class="data-container courses-container">
                        <label for="courses">คอร์สที่เคยเพิ่มแล้ว</label>
                        <input type="hidden" id="courseInput" name="courseInput" value="">
                        <?php if ($session->get('logged_in')) : ?>
                            <?php if (isset($courses) && is_array($courses)) : ?>
                                <?php foreach ($courses as $course) : ?>
                                    <div class="course-item"
                                        data-id="<?php echo htmlspecialchars($course['id']); ?>"
                                        data-term-id="<?php echo htmlspecialchars($course['id_term']); ?>"
                                        onclick="selectCourse(this)">
                                        <?php echo htmlspecialchars($course['Course_name']); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>ไม่มีข้อมูลคอร์ส</p>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                    <div class="data-container">
                        <div class="courses-list">
                            <h1>รายละเอียดคอร์สเรียน▾</h1>
                            <!-- <label for="courses"></label> -->

                        </div>
                    </div>
                    <br>
                    <br>



                </div>
                <br><br>
                <h1>เพิ่มรายละเอียดคอร์สเรียน</h1>
                <div class="input-group">
                    <input type="hidden" id="TermId" name="TermId" value="">
                    <input type="hidden" id="id_user" name="id_user" value="<?php echo htmlspecialchars($session->get('id')); ?>">
                    <input type="text" class="input-large" placeholder="พิมพ์รายละเอียดที่นี่" id="Course_name" name="Course_name" onclick="this.style.textAlign='left'; this.placeholder='';" onblur="if (this.value === '') { this.placeholder='พิมพ์รายละเอียดที่นี่'; this.style.textAlign='center'; }">
                    &nbsp;&nbsp;
                    <input type="text" class="input-small" placeholder="ราคาคอร์ส" id="Price_DC" name="Price_DC" onclick="this.style.textAlign='left'; this.placeholder='';" onblur="if (this.value === '') { this.placeholder='ราคาคอร์ส'; this.style.textAlign='center'; }">
                    &nbsp;<strong style="font-size: 25px; font-weight: 700;">THB</strong>&nbsp;&nbsp;
                    <div class="icons">
                        <button type="button" id="add-btn"><img src="/assets/image/Addicon.png" alt="Add Icon"></button>
                        <button type="button" class="edit-btn"><img src="/assets/image/Editicon.png" alt="Edit Icon"></button>
                        <button type="button" class="delete-btn"><img src="/assets/image/Deleteicon.png" alt="Delete Icon"></button>
                    </div>
                </div>
                <div class="save-button">
                    <button type="button" id="save-btn">บันทึก</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ฟังก์ชันจัดการคลาส 'active' สำหรับลิงก์ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('.content-header a');

            links.forEach(link => {
                // ตรวจสอบว่า URL ของลิงก์ตรงกับ URL ของหน้าปัจจุบัน
                if (link.href === window.location.href) {
                    link.classList.add('active');
                }

                link.addEventListener('click', (event) => {
                    event.preventDefault(); // ป้องกันการเปลี่ยนเส้นทางของลิงก์

                    // ลบคลาส 'active' จากลิงก์ทั้งหมด
                    links.forEach(link => link.classList.remove('active'));

                    // เพิ่มคลาส 'active' ให้กับลิงก์ที่ถูกคลิก
                    event.currentTarget.classList.add('active');

                    // เปลี่ยนเส้นทางไปที่ลิงก์ที่ถูกคลิก
                    window.location.href = event.currentTarget.href;
                });
            });
        });
    </script>

    <!-- ลิงค์ไปที่ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- ฟังก์ชันเลือกเทอม -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let coursesData = []; // เก็บข้อมูลคอร์สของทุกเทอม

            function selectTerm(termBox) {
                document.querySelectorAll('.term-item').forEach(item => item.classList.remove('selected'));
                termBox.classList.add('selected');

                let selectedTermId = termBox.dataset.id;
                document.getElementById('termInput').value = selectedTermId;

                document.querySelectorAll('.course-item').forEach(item => item.style.display = 'none');
                document.querySelectorAll(`.course-item[data-term-id="${selectedTermId}"]`).forEach(item => {
                    item.style.display = 'block';
                });

                updateCourseList(selectedTermId);
            }

            function updateCourseList(termId) {
                const courseList = document.querySelector('.courses-list');
                courseList.innerHTML = `<h1>รายละเอียดคอร์สเรียน▾</h1>`;

                const termData = coursesData.find(course => course.termId === termId);
                if (termData) {
                    termData.courses.forEach(course => {
                        const newCourseItem = document.createElement('div');
                        newCourseItem.classList.add('course-item');

                        // กำหนดชื่อเทอมจาก termId
                        const selectedTermElement = document.querySelector(`.term-item[data-id="${termId}"]`);
                        const termName = selectedTermElement ? selectedTermElement.textContent.trim() : 'ไม่ทราบชื่อเทอม';

                        newCourseItem.innerHTML = `
                    <strong>เทอม:</strong> <span class="term-name">${termName}</span><br>
                    <strong>คอร์ส:</strong> <span class="course-name">${course.courseName}</span><br>
                    <strong>ราคา:</strong> <span class="course-price">${course.price}</span><br>
                `;
                        courseList.appendChild(newCourseItem);
                    });
                }
            }

            // ซ่อนข้อมูลคอร์สทั้งหมดเมื่อเริ่มต้น
            document.querySelectorAll('.course-item').forEach(item => item.style.display = 'none');

            document.querySelectorAll('.term-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectTerm(this);
                });
            });

            const addButton = document.getElementById('add-btn');
            if (addButton) {
                addButton.addEventListener('click', function() {
                    const termInput = document.getElementById('termInput');
                    const selectedTermValue = termInput.value;
                    const courseNameInput = document.getElementById('Course_name');
                    const priceInput = document.getElementById('Price_DC');

                    if (!selectedTermValue) {
                        alert('กรุณาเลือกเทอมก่อนเพิ่มข้อมูลคอร์ส');
                        return;
                    }

                    const courseName = courseNameInput.value.trim();
                    const price = priceInput.value.trim();

                    if (!courseName || isNaN(price) || Number(price) <= 0) {
                        alert('กรุณากรอกรายละเอียดคอร์สและราคาที่ถูกต้อง');
                        return;
                    }

                    let termIndex = coursesData.findIndex(course => course.termId === selectedTermValue);
                    if (termIndex === -1) {
                        coursesData.push({
                            termId: selectedTermValue,
                            courses: [{
                                courseName,
                                price
                            }]
                        });
                    } else {
                        coursesData[termIndex].courses.push({
                            courseName,
                            price
                        });
                    }

                    updateCourseList(selectedTermValue);

                    courseNameInput.value = '';
                    priceInput.value = '';

                    alert('คอร์สถูกเพิ่มสำเร็จ!');
                });
            }

            const saveButton = document.querySelector('.save-button button');
            if (saveButton) {
                saveButton.addEventListener('click', async function() {
                    if (coursesData.length === 0) {
                        alert('ไม่มีข้อมูลคอร์สที่เพิ่มใหม่!');
                        return;
                    }

                    try {
                        const response = await fetch('/save-course', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(coursesData),
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();
                        if (data.success) {
                            alert('ข้อมูลคอร์สทั้งหมดถูกบันทึกแล้ว!');
                            coursesData.length = 0;
                            location.reload();
                        } else {
                            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                });
            }
        });
    </script>

    <script>
        document.getElementById('searchBox').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const courseItems = document.querySelectorAll('.course-item');

            courseItems.forEach(item => {
                const courseName = item.textContent.toLowerCase();
                item.style.display = courseName.includes(query) ? '' : 'none';
            });
        });
    </script>

</body>

</html>