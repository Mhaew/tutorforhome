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

    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>

<body>
    
    <?php
    $session = session();
    if (!$session->get('id')) {
        // หากผู้ใช้ไม่ได้ล็อกอินอยู่ ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
        return redirect()->to('/login');
    }
    ?>


<?= $this->include('layouts/inc/searchBox.php') ?>

<?= $this->include('layouts/inc/menu.php') ?>

<?= $this->renderSection('dashboard_term_content') ?>


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

    <!-- ฟังก์ชันซ่อนข้อมูลหลังจากล็อกเอาต์ -->
    <script>
        function hideDataAfterLogout() {
            // ซ่อนข้อมูลที่แสดงอยู่
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
    </script>

    <!-- ฟังก์ชันเลือกเทอม -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedTerm = null;
            let selectedTermId = null;
            let selectedCourseItem = null;

            const coursesData = []; // เก็บข้อมูลคอร์สของทุกเทอม

            function selectTerm(termBox) {
                if (selectedTerm) {
                    selectedTerm.classList.remove('selected');
                }
                selectedTerm = termBox;
                selectedTerm.classList.add('selected');
                selectedTermId = selectedTerm.dataset.id;
                document.getElementById('termInput').value = selectedTermId;
            }

            function validatePrice(price) {
                return !isNaN(price) && Number(price) > 0;
            }

            document.querySelectorAll('.term-item').forEach(item => {
                item.addEventListener('click', function() {
                    selectTerm(this);
                });
            });

            const addButton = document.getElementById('add-btn');
            if (addButton) {
                addButton.addEventListener('click', function() {
                    const termInput = document.getElementById('termInput');
                    const selectedTermValue = termInput ? termInput.value : null;
                    const courseNameInput = document.getElementById('Course_name');
                    const priceInput = document.getElementById('Price_DC');

                    if (!selectedTermValue) {
                        alert('กรุณาเลือกเทอมก่อนเพิ่มข้อมูลคอร์ส');
                        return;
                    }

                    const courseName = courseNameInput ? courseNameInput.value.trim() : '';
                    const price = priceInput ? priceInput.value.trim() : '';

                    if (!courseName || !price || !validatePrice(price)) {
                        alert('กรุณากรอกรายละเอียดคอร์สและราคาที่ถูกต้อง');
                        return;
                    }

                    // เก็บข้อมูลในอาร์เรย์ตาม termId
                    const termIndex = coursesData.findIndex(course => course.termId === selectedTermValue);
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

                    // อัปเดต UI
                    const courseList = document.querySelector('.courses-list');
                    if (courseList) {
                        const newCourseItem = document.createElement('div');
                        newCourseItem.classList.add('course-item');
                        newCourseItem.innerHTML = `
                    <strong>คอร์ส:</strong> <span class="course-name">${courseName}</span>
                    <strong>ราคา:</strong> <span class="course-price">${price}</span><br>
                `;
                        courseList.appendChild(newCourseItem);
                    }

                    termInput.value = '';
                    if (courseNameInput) courseNameInput.value = '';
                    if (priceInput) priceInput.value = '';
                    document.querySelectorAll('.term-item').forEach(item => item.classList.remove('selected'));
                    selectedTerm = null;
                    selectedTermId = null;

                    alert('คอร์สถูกเพิ่มแล้ว!');
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
                            coursesData.length = 0; // ล้างข้อมูลหลังบันทึกสำเร็จ
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