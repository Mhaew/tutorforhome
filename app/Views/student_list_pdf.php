<!-- student_list_pdf.php -->
<h1>รายชื่อนักเรียน</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>ชื่อ-นามสกุล</th>
            <th>คอร์สเรียน</th>
            <th>สถานะการชำระเงิน</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['id'] ?></td>
                <td><?= $student['name'] ?></td>
                <td><?= $student['course'] ?></td>
                <td><?= $student['payment_status'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>