<?= $this->extend('layouts/dashboard.php') ?>

<?php $this->section('dashboardcontent') ?>

<div class="content">
    <?php
    $session = session();
    if (!$session->get('id')) {
        // หากผู้ใช้ไม่ได้ล็อกอินอยู่ ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
        return redirect()->to('/login');
    }
    ?>

    <div class="content-header">
        <a href="/dashboard" class="active"><b>เพิ่มเทอม</b></a>
        <a href="/dashboard_term" class="inactive">เพิ่มคอร์สเรียน</a>
    </div>
    <div class="content-main">
        <!-- <div class="course-list">
            <h3><strong>เทอม 2567</strong></h3>
            <hr>
            <p>&nbsp;ซัมเมอร์ เมษายน 2567</p>
            <p>&nbsp;เทอม 1/2567</p>
            <p>&nbsp;ซัมเมอร์ ตุลาคม 2567</p>
            <p>&nbsp;เทอม 2/2567</p>
            <br>
            <h3><strong>เทอม 2568</strong></h3>
            <hr>
            <p>&nbsp;ซัมเมอร์ เมษายน 2568</p>
            <p>&nbsp;เทอม 1/2568</p>
            <p>&nbsp;ซัมเมอร์ ตุลาคม 2568</p>
            <p>&nbsp;เทอม 2/2568</p>
        </div> -->

        <div class="course-details">
            <form id="term-form" action="<?= site_url('dashboard/saveterm') ?>" method="post" class="table">
                <h1>รายละเอียดเทอม</h1>
                <div id="term-list" name="term-list" class="d-flex flex-wrap table">
                    <!-- แสดงข้อความที่เพิ่ม -->
                </div>
                <br>
                <h1>เพิ่มรายละเอียดเทอม</h1>
                <div class="input-group">
                    <input type="text" class="form-control-custom element" id="Term_name" name="Term_name" placeholder="พิมพ์รายละเอียดเทอม" onclick="this.style.textAlign='left'; this.placeholder='';" onblur="if (this.value === '') { this.placeholder='พิมพ์รายละเอียดเทอม'; this.style.textAlign='center'; }">
                    <div class="icons">
                        <button type="button" id="add-btn"><img src="/assets/image/Addicon.png" alt="Add Icon"></button>
                        <button type="button" id="edit-btn"><img src="/assets/image/Editicon.png" alt="Edit Icon"></button>
                        <button type="button" id="delete-btn"><img src="/assets/image/Deleteicon.png" alt="Delete Icon"></button>
                    </div>
                </div>
                <div class="save-button">
                    <button type="button" id="save-btn">บันทึก</button>
                </div>
            </form>

        </div>

    </div>
    <table id="TermsTable" class="display table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>เทอม</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
    </table>
</div>



<div class="modal fade" id="editTermModal" tabindex="-1" aria-labelledby="editTermModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTermModalLabel">แก้ไขข้อมูลเทอม</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTermForm">
                    <input type="hidden" id="id_edit" name="id">
                    <div class="mb-3">
                        <label for="term_name_edit" class="form-label">รายละเอียดเทอม</label>
                        <input type="text" class="form-control" id="term_name_edit" name="Term_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">อัปเดต</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- โหลด jQuery ก่อน -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#TermsTable').DataTable({
            "ajax": {
                "url": "/dashboard/fetchTerms", // ตรวจสอบว่า URL ถูกต้อง
                "dataSrc": "data"
            },
            "columns": [{
                    "data": "id"
                },
                {
                    "data": "term_detail"
                }, // ใช้ key ให้ตรงกับ response
                {
                    "data": "id",
                    "render": function(data, type, row) {
                        return '<button class="btn btn-warning btn-edit" data-id="' + data + '">แก้ไข</button>' +
                            '<button class="btn btn-danger deleteBtn" data-id="' + data + '">ลบ</button>';
                    }
                }
            ]
        });

        // ปุ่มลบ
        $('#TermsTable').on('click', '.deleteBtn', function() {
            var TermId = $(this).data('id');
            if (confirm('คุณต้องการลบข้อมูลนี้ใช่หรือไม่?')) {
                $.ajax({
                    url: '/dashboard/deleteTerm/' + TermId,
                    method: 'DELETE',
                    success: function(response) {
                        alert('ลบสำเร็จ');
                        table.ajax.reload(); // รีเฟรช DataTable
                    },
                    error: function(xhr, status, error) {
                        alert('เกิดข้อผิดพลาดในการลบข้อมูล');
                    }
                });
            }
        });

        // ปุ่มแก้ไข (ดึงข้อมูลมาแสดงใน Modal)
        $('#TermsTable').on('click', '.btn-edit', function() {
            var TermId = $(this).data('id');
            $.ajax({
                url: '/dashboard/getTermDetails/' + TermId,
                method: 'GET',
                success: function(response) {
                    $('#id_edit').val(response.id);
                    $('#term_name_edit').val(response.Term_name); // ใช้ key ให้ถูกต้อง
                    $('#editTermModal').modal('show');
                },
                error: function(xhr, status, error) {
                    alert('ไม่สามารถดึงข้อมูลได้');
                }
            });
        });

        // Form แก้ไข
        $('#editTermForm').on('submit', function(e) {
            e.preventDefault(); // ป้องกันการโหลดหน้าใหม่

            var formData = $(this).serialize(); // ดึงข้อมูลทั้งหมดจากฟอร์ม
            console.log("Sending Data: ", formData); // Debug ข้อมูลก่อนส่ง

            $.ajax({
                url: '/dashboard/updateTerms', // URL สำหรับการอัปเดตข้อมูล
                method: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Server Response: ", response); // Debug ค่าที่เซิร์ฟเวอร์ส่งกลับ
                    if (response.success) {
                        alert('ข้อมูลถูกอัปเดตเรียบร้อย');
                        $('#editTermModal').modal('hide');
                        location.reload(); // รีเฟรช DataTable
                    } else {
                        alert(response.message || "อัปเดตไม่สำเร็จ");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Update Error: ", xhr.responseText);
                    alert('เกิดข้อผิดพลาดในการอัปเดตข้อมูล');
                }
            });
        });

    });
</script>


<script>
    class TermManager {
        constructor(termInputId, termListSelector) {
            this.termInput = document.getElementById(termInputId);
            this.termList = document.querySelector(termListSelector);
            this.selectedTerm = null;
            this.initEventListeners();
        }

        initEventListeners() {
            document.getElementById('add-btn').addEventListener('click', () => this.addTerm());
            document.getElementById('edit-btn').addEventListener('click', () => this.editTerm());
            document.getElementById('delete-btn').addEventListener('click', () => this.deleteTerm());
        }

        addTerm() {
            const termName = this.termInput.value.trim();
            if (termName) {
                const existingTerm = Array.from(this.termList.children).find(
                    (termBox) => termBox.textContent === termName
                );

                if (existingTerm) {
                    alert('คุณได้เพิ่มเทอมนี้ไปแล้ว');
                } else {
                    const termBox = document.createElement('div');
                    termBox.textContent = termName;
                    termBox.className = 'term-box';
                    termBox.addEventListener('click', () => this.selectTerm(termBox));
                    this.termList.appendChild(termBox);
                    this.termInput.value = '';
                }
            } else {
                alert('กรุณาป้อนข้อมูลเทอม');
            }
        }

        selectTerm(termBox) {
            if (this.selectedTerm) {
                this.selectedTerm.classList.remove('selected');
            }
            this.selectedTerm = termBox;
            this.selectedTerm.classList.add('selected');
            this.termInput.value = this.selectedTerm.textContent;
        }

        editTerm() {
            if (this.selectedTerm) {
                const newTermName = this.termInput.value.trim();
                if (newTermName) {
                    this.selectedTerm.textContent = newTermName;
                    this.termInput.value = '';
                    this.selectedTerm.classList.remove('selected');
                    this.selectedTerm = null;
                } else {
                    alert('Please enter a term name.');
                }
            } else {
                alert('Please select a term to edit.');
            }
        }

        deleteTerm() {
            if (this.selectedTerm) {
                this.termList.removeChild(this.selectedTerm);
                this.termInput.value = '';
                this.selectedTerm = null;
            } else {
                alert('Please select a term to delete.');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new TermManager('Term_name', '#term-list');

        const saveButton = document.getElementById('save-btn');

        saveButton.addEventListener('click', () => {
            const termList = document.querySelectorAll('#term-list .term-box');
            const termListData = Array.from(termList).map(term => term.textContent).join(',');

            fetch('<?= site_url('dashboard/saveterm') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'term_list_data': termListData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('บันทึกข้อมูลสำเร็จ');
                        location.reload();
                    } else {
                        alert('เกิดข้อผิดพลาด: รายละเอียดเทอมซ้ำ');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                });
        });

        const links = document.querySelectorAll('.content-header a');

        links.forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }

            link.addEventListener('click', (event) => {
                event.preventDefault();

                links.forEach(link => link.classList.remove('active'));

                event.currentTarget.classList.add('active');
                window.location.href = event.currentTarget.href;
            });
        });
    });
</script>



<?php $this->endSection('dashboardcontent') ?>