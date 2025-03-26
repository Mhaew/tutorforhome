<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');

// app/Config/Routes.php

$routes->get('/register', 'Register::index'); // Route to display registration form
$routes->post('/register/save', 'Register::save'); // Route to process registration form submission

$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');

$routes->get('/index', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('/dashboard', 'Dashboard::dashboard', ['filter' => 'auth']);
$routes->get('/dashboard/getTermDetails/(:num)', 'Dashboard::getTermDetails/$1');
$routes->delete('/dashboard/deleteTerm/(:num)', 'Dashboard::deleteTerm/$1'); // เปลี่ยน (:segment) เป็น (:num)
$routes->get('/dashboard/fetchTerms', 'Dashboard::fetchTerms');
$routes->post('/dashboard/updateTerms', 'Dashboard::updateTerms');


// เส้นทางสำหรับ POST requests
$routes->post('dashboard/saveterm', 'Dashboard::saveterm');

$routes->post('/save-course', 'CourseController::saveCourse');
$routes->get('/getcoursesbyterm', 'CourseController::getCoursesByTerm');

// app/Config/Routes.php
$routes->get('/forgot_password', 'ForgotPassword::index');
$routes->post('/forgot_password/submit', 'ForgotPassword::submit');
// Route for ResetPassword Controller
$routes->get('reset_password/(:segment)', 'ResetPassword::index/$1');
$routes->post('reset_password/submit', 'ResetPassword::submit');

$routes->get('/studypage', 'Studypage::studypage');
$routes->post('/save-study', 'Studypage::saveStudy');
$routes->post('/getcourses', 'Studypage::getCourses');

$routes->get('/billpage', 'BillController::billpage');
$routes->get('/printBill', 'BillController::printBill');
$routes->post('/billpage/fetchStudys', 'BillController::fetchStudys');
$routes->post('/billpage/confirm-payment', 'BillController::confirmPayment');
$routes->post('billpage/payMore', 'BillController::payMore');
$routes->get('billpage/getRemainingAmount/(:num)', 'BillController::getRemainingAmount/$1');
$routes->get('billpage/printReceipt', 'BillController::printReceipt');

$routes->get('studentpage/generateNames', 'StudyController::generateNames');
$routes->post('/addPaymentAmount', 'BillController::addPaymentAmount');


$routes->get('generate-excel/(:num)', 'ReceiptController::generateExcel/$1');

// ใน Routes.php


$routes->get('/studentpage', 'StudentController::studentpage');
$routes->post('/studentpage/fetchStudys', 'StudentController::fetchStudys');
$routes->post('/studentpage/confirm-payment', 'StudentController::confirmPayment');
$routes->post('studentpage/payMore', 'StudentController::payMore');
$routes->get('studentpage/getRemainingAmount/(:num)', 'StudentController::getRemainingAmount/$1');
$routes->get('/studentpage/generateNames/(:num)', 'StudentController::generateNames/$1');
$routes->get('studentpage/printReceipt', 'StudentController::printReceipt');
$routes->post('student/getStudentCountByTermAndCourse', 'StudentController::getStudentCountByTermAndCourse');
$routes->delete('studentpage/deleteStudent/(:num)', 'StudentController::deleteStudent/$1');

$routes->post('/studentpage/getStudentCount', 'StudentController::getStudentCount');

$routes->post('/studentpage/confirmEditOpen', 'StudentController::confirmEditOpen');

$routes->post('studentpage/getStudyCount', 'StudentController::getStudyCount');


$routes->post('/updateOpenCount', 'StudentController::updateOpenCount');
$routes->get('/printStudent', 'StudentController::printStudent');

$routes->post('studentpage/deleteStudy/(:num)', 'StudentController::deleteStudy/$1');

$routes->post('/deleteSelectedStudies', 'StudentController::deleteSelectedStudies');

$routes->get('generateNames', 'StudentController::generateNames');

// ในไฟล์ app/Config/Routes.php
$routes->get('/getStudyDetails', 'StudentController::getStudyDetails');

$routes->post('/confirmEditOpen', 'StudentController::confirmEditOpen');


// $routes->get('employeepage/generateNames', 'EmployeeCr::generateNames');

// $routes->get('/employeepage', 'EmployeeCr::employeepage');
// $routes->post('/employeepage/fetchStudys', 'EmployeeCr::fetchStudys');
// $routes->post('/employeepage/fetchUsers', 'EmployeeCr::fetchUsers');
// $routes->post('/employeepage/confirm-payment', 'EmployeeCr::confirmPayment');
// $routes->post('/employeepage/deleteUser', 'EmployeeCr::deleteUser');

// $routes->get('employeepage/getRemainingAmount/(:num)', 'EmployeeCr::getRemainingAmount/$1');
// $routes->get('employeepage/printReceipt', 'EmployeeCr::printReceipt');

$routes->get('/employeepage', 'EmployeeCr::employeepage');
$routes->post('/employeepage/saveUser', 'EmployeeCr::saveUser');
$routes->get('/employeepage/getUser/(:num)', 'EmployeeCr::getUser/$1');
$routes->post('/employeepage/addUser', 'EmployeeCr::addUser');
$routes->get('/employeepage/fetchUserById/(:num)', 'EmployeeCr::fetchUserById/$1');

$routes->get('/employeepage/getUserDetails/(:num)', 'EmployeeCr::getUserDetails/$1');
$routes->delete('/employeepage/deleteUser/(:segment)', 'EmployeeCr::deleteUser/$1');
$routes->get('employeepage/fetchUsers', 'EmployeeCr::fetchUsers');

 
$routes->post('/employeepage/updateUser', 'EmployeeCr::updateUser');

// $routes->get('userspage/generateNames', 'EmployeeCr::generateNames');

// $routes->get('/userspage', 'UsersController::userspage');
// $routes->post('/userspage/fetchStudys', 'UsersController::fetchStudys');
// $routes->get('/userspage/fetchUsers', 'UsersController::fetchUsers');
// $routes->post('/userspage/fetchUsers', 'UsersController::fetchUsers');
// $routes->post('/userspage/confirm-payment', 'UsersController::confirmPayment');
// $routes->post('/userspage/deleteUser', 'UsersController::deleteUser');

// $routes->get('userspage/getRemainingAmount/(:num)', 'UsersController::getRemainingAmount/$1');
// $routes->get('userspage/printReceipt', 'UsersController::printReceipt');

$routes->get('/userspage', 'UsersController::userspage');
$routes->post('/userspage/fetchUsers', 'UsersController::fetchUsers');
$routes->post('/userspage/saveUser', 'UsersController::saveUser');
$routes->get('/userspage/getUser/(:num)', 'UsersController::getUser/$1'); // Corrected this line
$routes->post('/userspage/deleteUser', 'UsersController::deleteUser');





$routes->get('/paymentpage', 'Paymentpage::paymentpage');
$routes->post('/fetchStudys', 'Paymentpage::fetchStudys');
$routes->post('/confirm-payment', 'Paymentpage::confirmPayment');

$routes->post('/deleteStudy', 'Paymentpage::deleteStudy');


// $routes->get('/billpage', 'billController::billpage');
// $routes->post('/bill/fetchStudys', 'billController::fetchStudys');
// $routes->post('/bill/confirm-payment', 'billController::confirmPayment');

$routes->get('/dashboard_term', 'Dashboard_term::dashboard_term');

$routes->get('/logout', 'Logout::logout');