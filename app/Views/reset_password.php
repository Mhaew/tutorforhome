<!-- app/Views/reset_password.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #fff;
            color: #333;
        }

        .container-custom {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-control-custom {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 50px;
            box-sizing: border-box;
            background-color: #EFEFEF;
            font-size: 14px;
            font-family: 'Nunito', sans-serif;
        }

        .btn-custom {
            background-color: #FF0000;
            color: white;
            border-radius: 50px;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-family: 'Nunito', sans-serif;
            transition: background-color 0.3s;
        }

        .btn-custom:hover,
        .btn-custom:focus,
        .btn-custom:active {
            background-color: #000;
            outline: none;
        }

        a {
            text-decoration: none;
            font-size: 14px;
            font-family: 'Nunito', sans-serif;
            color: #EE090A;
            transition: color 0.3s;
        }

        a:hover,
        a:active {
            color: #000000;
        }

        h1,
        p {
            font-family: 'Nunito', sans-serif;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-custom">
        <div class="form-container">
            <h1>Reset Password</h1>
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><?= $validation->listErrors(); ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <form action="/reset_password/submit" method="post">
                <input type="hidden" name="token" value="<?= $token; ?>">

                <div class="label-container">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control-custom" id="password" name="password">
                </div>
                <div class="label-container">
                    <label for="confpassword" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control-custom" id="confpassword" name="confpassword">
                </div>

                <div class="d-grid">
                    <button class="btn btn-lg btn-custom" type="submit">Reset Password</button>
                </div>
            </form>
            <br>
            <p> <a href="/login"><strong>Login</strong></a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>