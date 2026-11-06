<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$d = student_input([]);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $d = student_input($_POST);
    $errors = validate_student($d);
    if (!$errors) {
        try {
            db()->prepare('INSERT INTO students (student_no, first_name, middle_name, last_name, gender, email, contact_no,
                                                 program, year_level, section, enrollment_status, date_enrolled, status)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)')
                ->execute([$d['student_no'], $d['first_name'], $d['middle_name'] ?: null, $d['last_name'], $d['gender'],
                           $d['email'], $d['contact_no'], $d['program'], $d['year_level'], $d['section'],
                           $d['enrollment_status'], $d['date_enrolled'], $d['status']]);
            flash('success', 'Student added.');
            redirect('students.php');
        } catch (PDOException $ex) {
            if (!is_duplicate($ex)) throw $ex;
            if (stripos($ex->getMessage(), 'student_no') !== false) $errors['student_no'] = 'That student number already exists.';
            else $errors['email'] = 'That email is already used.';
        }
    }
}

$pageTitle = 'Add Student | Student Management System';
$active = 'students';
$submitLabel = 'Save student';
require __DIR__ . '/includes/header.php';
?>
<h2 class="h3 mb-0">Add Student</h2>
<p class="text-body-secondary small mb-3">Dashboard &gt; Students &gt; Add Student</p>
<?php require __DIR__ . '/includes/student_form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
