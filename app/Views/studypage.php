<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Page</title>
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
                <li><a href="/studypage" class="add-course">• ข้อมูลสมัครเรียน</a></li>
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

        <h1>กรอกข้อมูลสมัครเรียน</h1>
        <p> ข้อมูลผู้สมัครเรียน
        <p>
        <div class="input-group">
            <input type="hidden" id="ID_Terms" name="ID_Terms" value="">
            <input type="hidden" id="ID_Courses" name="ID_Courses" value="">
            <input type="text" id="Title_name" name="Title_name" class="form-control-custom element small-input" placeholder="คำนำหน้า"
                style="width: 7%;"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='คำนำหน้า'; this.style.textAlign='center'; }">
            <input type="text" id="Firstname_S" name="Firstname_S" class="form-control-custom element" placeholder="ชื่อจริง"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='ชื่อจริง'; this.style.textAlign='center'; }">
            <input type="text" id="Lastname_S" name="Lastname_S" class="form-control-custom element" placeholder="นามสกุล"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='นามสกุล'; this.style.textAlign='center'; }">
            <input type="text" id="Phone_S" name="Phone_S" class="form-control-custom element" placeholder="เบอร์โทร"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='เบอร์โทร'; this.style.textAlign='center'; }">
        </div>
        <br>
        <p> ข้อมูลผู้ปกครอง
        <p>
        <div class="input-group">
            <input type="text" id="Firstname_P" name="Firstname_P" class="form-control-custom element" placeholder="ชื่อจริง"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='ชื่อจริง'; this.style.textAlign='center'; }">
            <input type="text" id="Lastname_P" name="Lastname_P" class="form-control-custom element" placeholder="นามสกุล"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='นามสกุล'; this.style.textAlign='center'; }">
            <input type="text" id="Phone_P" name="Phone_P" class="form-control-custom element" placeholder="เบอร์โทร"
                onclick="this.style.textAlign='left'; this.placeholder='';"
                onblur="if (this.value === '') { this.placeholder='เบอร์โทร'; this.style.textAlign='center'; }">
        </div>
        <br>
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
            </div>

            <div class="data-container courses-container">
                <label for="courses">เลือกคอร์ส▾</label>
                <input type="hidden" id="courseInput" name="courseInput" value="">
                <div class="course-items">
                    <?php if ($session->get('logged_in')) : ?>
                        <?php if (isset($courses)) : ?>
                            <?php foreach ($courses as $course) : ?>
                                <div class="course-item" data-id="<?php echo htmlspecialchars($course['id']); ?>" onclick="selectCourse(this)">
                                    <?php echo htmlspecialchars($course['Course_name']); ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p><?php echo htmlspecialchars($message); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="input-item" style="margin-top: auto;">
                <h4> ยอดชำระเงิน</h4>
                <div>
                    <label>
                        <input type="radio" id="Status_Price" name="Status_Price" value="full" checked> ชำระเต็มจำนวน
                    </label>
                    <label>
                        <input type="radio" id="Status_Price" name="Status_Price" value="deposit"> มัดจำ
                    </label>
                </div>
                <input type="number" name="Total" class="form-control-custom element" id="Total" placeholder="ระบุจำนวนเงิน" style="color: #5d5d5d; width: 300px;"
                    onclick="this.style.textAlign='center'; this.placeholder='';"
                    onblur="if (this.value === '') { this.placeholder='ระบุจำนวนเงิน'; this.style.textAlign='center'; }">
                <strong style="font-size: 25px; font-weight: 700;">THB</strong>
            </div>
        </div>

        <br>

        <div class="input-group">
            <div class="input-group">
                <div class="input-item">
                    <h4> ส่วนลด</h4>
                    <input type="text" id="Discount" name="Discount" class="form-control-custom element" placeholder="ส่วนลด"
                        style="color: #5d5d5d; width: 250px;" value="0"
                        oninput="calculateFinalPrice()">
                </div>
                <div class="input-item">
                    <h4> ราคาจริง</h4>
                    <input type="int" id="realPrice" name="realPrice" class="form-control-custom element" placeholder="ราคาจริง"
                        style="color: #5d5d5d; width: 250px; text-align: center;"
                        readonly>
                </div>
                <div class="input-item">
                    <h4> ราคาภาษาไทย</h4>
                    <input type="text" id="Price_thai" name="Price_thai" class="form-control-custom" placeholder="ราคาภาษาไทย"
                        readonly style="color: #5d5d5d; width: 250px;">
                </div>
                <input type="hidden" id="balance" name="balance">
            </div>
        </div>

        <div class="save-button">
            <button type="button" id="save-btn">บันทึก</button>
        </div>

        <!-- ลิงค์ไปที่ Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

        <!-- ฟังก์ชันเลือกเทอม -->
        <script>
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
                        const result = await response.json();

                        // ตรวจสอบว่า API ส่งผลลัพธ์ที่มี success = true หรือไม่
                        if (result.success === false) {
                            console.error('Error:', result.message); // แสดงข้อความข้อผิดพลาด
                            alert(result.message); // แสดงข้อความให้ผู้ใช้ทราบ
                            return; // หยุดการทำงานหากไม่พบข้อมูล
                        }

                        const courses = result.courses; // สมมติว่า API ส่งข้อมูลคอร์สใน result.courses

                        if (!Array.isArray(courses)) {
                            console.error('Expected an array of courses, but received:', courses);
                            return;
                        }

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
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const realPriceInput = document.getElementById('realPrice');
                const totalInput = document.getElementById('Total');
                const balanceInput = document.getElementById('balance');

                // Function to calculate the balance
                function calculateBalance() {
                    const realPrice = parseFloat(realPriceInput.value) || 0; // Default to 0 if empty
                    const total = parseFloat(totalInput.value) || 0; // Default to 0 if empty
                    const balance = realPrice - total;

                    balanceInput.value = balance.toFixed(2); // Set value with 2 decimal places
                }

                // Attach event listeners to calculate the balance whenever inputs change
                realPriceInput.addEventListener('input', calculateBalance);
                totalInput.addEventListener('input', calculateBalance);
            });
        </script>

        <!-- เซฟ -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('save-btn').addEventListener('click', function() {
                    // Check if elements exist
                    const ID_Terms = document.getElementById('ID_Terms');
                    const ID_Courses = document.getElementById('ID_Courses');
                    const Title_name = document.getElementById('Title_name');
                    const Firstname_S = document.getElementById('Firstname_S');
                    const Lastname_S = document.getElementById('Lastname_S');
                    const Phone_S = document.getElementById('Phone_S');
                    const Firstname_P = document.getElementById('Firstname_P');
                    const Lastname_P = document.getElementById('Lastname_P');
                    const Phone_P = document.getElementById('Phone_P');
                    const realPrice = document.getElementById('realPrice');
                    const Status_Price = document.querySelector('input[name="Status_Price"]:checked'); // Ensure you're selecting the checked radio button
                    const Total = document.getElementById('Total');
                    const Discount = document.getElementById('Discount');
                    const Price_thai = document.getElementById('Price_thai');
                    const balance = document.getElementById('balance');

                    // Ensure that elements are found
                    if (!ID_Terms || !ID_Courses) {
                        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                        return;
                    }

                    // Create FormData objects
                    const formData = new FormData();
                    formData.append('ID_Terms', ID_Terms.value);
                    formData.append('ID_Courses', ID_Courses.value);
                    formData.append('Title_name', Title_name.value);
                    formData.append('Firstname_S', Firstname_S.value);
                    formData.append('Lastname_S', Lastname_S.value);
                    formData.append('Phone_S', Phone_S.value);
                    formData.append('Firstname_P', Firstname_P.value);
                    formData.append('Lastname_P', Lastname_P.value);
                    formData.append('Phone_P', Phone_P.value);
                    formData.append('Status_Price', Status_Price.value);
                    formData.append('Total', Total.value);
                    formData.append('realPrice', realPrice.value);
                    formData.append('Discount', Discount.value);
                    formData.append('Price_thai', Price_thai.value);
                    formData.append('balance', balance.value);

                    // ตรวจสอบว่า balance ต้องเป็นศูนย์เมื่อชำระเต็มจำนวน
                    if (Status_Price.value === 'full' && parseFloat(balance.value) !== 0) {
                        alert('คุณใส่จำนวนเงินไม่ถูกต้อง กรุณาตรวจสอบใหม่');
                        return; // หยุดการดำเนินการหากไม่ผ่านเงื่อนไข
                    }

                    // ตรวจสอบว่า balance และ Total ต้องไม่น้อยกว่าศูนย์เมื่อชำระเป็นมัดจำ และ Total ต้องไม่ต่ำกว่า 2800
                    if (Status_Price.value === 'deposit') {
                        const totalAmount = parseFloat(Total.value); // ค่า Total ที่กรอก
                        const balanceAmount = parseFloat(balance.value); // ค่า balance ที่คำนวณ

                        if (balanceAmount <= 0 || totalAmount <= 0) {
                            alert('ยอดชำระต้องไม่ต่ำกว่า 2800 บาท และต้องไม่เท่ากับหรือมากกว่าราคาจริง กรุณากรอกอีกครั้ง');
                            return; // หยุดการดำเนินการหากไม่ผ่านเงื่อนไข
                        }

                        // ตรวจสอบว่า Total ต้องไม่ต่ำกว่า 2800
                        if (totalAmount < 2800) {
                            alert('ยอดชำระต้องไม่ต่ำกว่า 2800 บาท และต้องไม่เท่ากับหรือมากกว่าราคาจริง กรุณากรอกอีกครั้ง');
                            return; // หยุดการดำเนินการหากไม่ผ่านเงื่อนไข
                        }
                    }






                    // Debugging with get() method
                    console.log(formData.get('ID_Terms'));
                    console.log(formData.get('ID_Courses'));
                    console.log(formData.get('Title_name'));
                    console.log(formData.get('Firstname_S'));
                    console.log(formData.get('Lastname_S'));
                    console.log(formData.get('Phone_S'));
                    console.log(formData.get('Firstname_P'));
                    console.log(formData.get('Lastname_P'));
                    console.log(formData.get('Phone_P'));
                    console.log(formData.get('Status_Price'));
                    console.log(formData.get('Total'));
                    console.log(formData.get('realPrice'));
                    console.log(formData.get('Discount'));
                    console.log(formData.get('Price_thai'));
                    console.log(formData.get('balance'));

                    // Debugging with forEach()
                    // formData.forEach((value, key) => {
                    //     console.log(key + ': ' + value);
                    // });

                    // return;

                    // Send data to server
                    fetch('/save-study', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('ข้อมูลถูกบันทึกแล้ว!');
                                location.reload();
                            } else {
                                alert('กรุณากรอกข้อมูลให้ครบถ้วน');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                        });
                });
            });
        </script>
</body>

</html>