<?php
$session = session();
if (!$session->get('id')) {
    // หากผู้ใช้ไม่ได้ล็อกอินอยู่ ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
    return redirect()->to('/login');
}
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