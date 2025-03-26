<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>

    <!-- ลิงค์ไปที่ Bootstrap CSS สำหรับการใช้งานฟังก์ชันและการจัดรูปแบบ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- ลิงค์ไปที่ Google Fonts เพื่อใช้ฟอนต์ Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* ตั้งค่าพื้นฐานสำหรับตัวอักษรและสีพื้นหลัง */
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #fff;
            color: #333;
        }

        /* ตั้งค่าการจัดการสำหรับ container หลักให้กลางหน้าจอ */
        .container-custom {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            /* แบ่งพื้นที่เป็นสองคอลัมน์เท่ากัน */
            gap: 2rem;
            /* ระยะห่างระหว่างคอลัมน์ */
            justify-items: center;
            /* จัดเรียงเนื้อหาในแนวนอนตามศูนย์กลาง */
            align-items: center;
            /* จัดเรียงเนื้อหาในแนวตั้งตามศูนย์กลาง */
            height: 90vh;
        }

        /* ตั้งค่าการจัดการสำหรับรูปภาพในคอนเทนท์ทางด้านซ้าย */
        .left-content img {
            max-width: 90%;
        }

        .left-content {
            justify-self: end;
            /* จัดให้ .left-content ชิดขวาของพื้นที่ที่กำหนด */
        }

        .right-content {
            justify-self: start;
            /* จัดให้ .right-content ชิดซ้ายของพื้นที่ที่กำหนด */
        }

        /* ตั้งค่าการจัดการสำหรับปุ่ม */
        .btn-custom {
            background-color: #FF0000;
            color: white;
            border-radius: 50px;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-family: 'Nunito', sans-serif;
            transition: background-color 0.3s;
            width: 160px;
            /* ปรับความยาวของปุ่ม */
            margin: 0 auto;
            /* ทำให้ปุ่มอยู่ตรงกลาง */
        }

        .btn-custom:hover,
        .btn-custom:focus,
        .btn-custom:active {
            background-color: #000000;
            outline: none;
            color: white;
            /* สีตัวอักษร */
        }

        /* ตั้งค่าการจัดการสำหรับ input fields */
        .form-control-custom {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            display: inline-block;
            border: none;
            border-radius: 50px;
            box-sizing: border-box;
            background-color: #EFEFEF;
            font-size: 14px;
            font-family: 'Nunito', sans-serif;
        }

        /* เมื่อมีการคลิก */
        .element:focus,
        .element:active {
            border: 1px solid #ccc;
            /* สีเส้นขอบเมื่อคลิกหรือโฟกัส */
            outline: none;
            /* ลบเส้นกรอบมาตรฐานของเบราว์เซอร์ */
        }

        /* ตั้งค่าการจัดการสำหรับลิงก์ */
        a {
            text-decoration: none;
            font-size: 14px;
            font-family: 'Nunito', sans-serif;
            color: #EE090A;
            transition: color 0.3s;
            font-weight: 800;
        }

        a:hover,
        a:active {
            color: #000000;
        }

        /* ตั้งค่าการจัดการสำหรับ h1 */
        h1 {
            font-size: 40px;
            font-family: 'Nunito', sans-serif;
            color: #EE090A;
            text-align: center;
            font-weight: 900;
        }

        /* ตั้งค่าการจัดการสำหรับ p */
        p {
            font-size: 14px;
            font-family: 'Nunito', sans-serif;
            color: #EE090A;
            text-align: center;
        }

        /* ตั้งค่าการจัดการสำหรับ label และ input fields */
        .label-container {
            position: relative;
            font-size: 14px;
            font-family: 'Nunito', sans-serif;
            margin-bottom: 5px;
            /* เพิ่มช่องว่างด้านล่างสำหรับกล่องข้อความ */
        }

        .label-container label {
            position: absolute;
            left: 10px;
            height: 10px;
            line-height: 0px;
        }
    </style>

</head>

<body>

    <div class="container-custom">
        <!-- ส่วนซ้ายที่มีรูปภาพ -->
        <div class="left-content">
            <img src="/assets/image/tutorforhome.png" alt="Tutor for Home">
        </div>
        <!-- ส่วนขวาที่มีฟอร์ม -->
        <div class="right-content">
            <h1> Sign up </h1>
            <br>
            <!-- แสดงข้อความเตือนหากมี -->
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><?= $validation->listErrors(); ?></div>
            <?php endif; ?>
            <!-- ฟอร์มสำหรับการสมัคร -->
            <form action="/register/save" method="post">
                <div class="label-container">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control-custom element" id="username" name="username" value="<?= set_value('username'); ?>">
                </div>
                <div class="label-container">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control-custom element" id="email" name="email" value="<?= set_value('email'); ?>">
                </div>
                <div class="label-container">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control-custom element" id="password" name="password">
                </div>
                <div class="label-container">
                    <label for="confpassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control-custom element" id="confpassword" name="confpassword">
                </div>
                <div class="label-container">
                    <label for="first_name" class="form-label">First name</label>
                    <input type="text" class="form-control-custom element" id="first_name" name="first_name" value="<?= set_value('first_name'); ?>">
                </div>
                <div class="label-container">
                    <label for="last_name" class="form-label">Last name</label>
                    <input type="text" class="form-control-custom element" id="last_name" name="last_name" value="<?= set_value('last_name'); ?>">
                </div>

                <div class="d-grid">
                    <button class="btn btn-lg btn-custom" type="submit">Register</button>
                </div>
            </form>
            <br>
            <p>Already Registered? <a href="/login"> Login </a> | <a href="/forgot_password"> forget your password </a></p>
        </div>
    </div>

    <!-- ลิงค์ไปที่ Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>