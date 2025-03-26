<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ใบเสร็จรับเงิน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .receipt {
            width: 300px;
            padding: 20px;
            border: 1px solid #000;
            margin-bottom: 20px;
        }

        .btn-print {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php foreach ($receipts as $receipt): ?>
        <div class="receipt">
            <h2>ใบเสร็จรับเงิน</h2>
            <p>หมายเลขใบเสร็จ: <?= $receipt['ID_Study']; ?></p>
            <p>ชื่อ-นามสกุล: <?= $receipt['Firstname_S'] . ' ' . $receipt['Lastname_S']; ?></p>
            <p>ยอดชำระเงิน: <?= $receipt['Total']; ?> บาท</p>
            <p>สถานะ: <?= $receipt['Status_Price'] == 'full' ? 'ชำระเต็มจำนวน' : 'มัดจำ'; ?></p>
        </div>
    <?php endforeach; ?>

    <button class="btn-print" onclick="window.print()">พิมพ์</button>
</body>

</html>