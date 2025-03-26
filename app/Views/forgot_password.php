<!-- app/Views/forgot_password.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <div class="container-custom custom-page">
        <!-- ส่วนซ้ายที่มีรูปภาพ -->
        <div class="left-content">
            <img src="/assets/image/tutorforhome.png" alt="Tutor for Home">
        </div>
        <!-- ส่วนขวาที่มีฟอร์ม -->
        <div class="right-content">


            <div class="form-container">
                <h1><strong>Forgot Password</strong></h1>
                <br>
                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger"><?= $validation->listErrors(); ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <form action="/forgot_password/submit" method="post">
                    <div class="label-container">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control-custom element" id="email" name="email">
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-lg btn-custom" type="submit">Send Reset Link</button>
                    </div>
                </form>
                <br>
                <p> <a href="/login"><strong>Login</strong></a></p>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>