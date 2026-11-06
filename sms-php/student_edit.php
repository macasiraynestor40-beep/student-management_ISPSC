<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$s = find_student($id);
if (!$s) { flash('danger', 'Student not found.'); redirect('students.php'); }

$d = student_input($s);   // pre-fill the form from the database row
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $d = student_input($_POST);
    $errors = validate_student($d);
    if (!$errors) {
        try {
            db()->prepare('UPDATE students SET student_no=?, first_name=?, middle_name=?, last_name=?, gender=?, email=?,
                                  contact_no=?, program=?, year_level=?, section=?, enrollment_status=?, date_enrolled=?, status=?
                           WHERE student_id=?')
                ->execute([$d['student_no'], $d['first_name'], $d['middle_name'] ?: null, $d['last_name'], $d['gender'],
                           $d['email'], $d['contact_no'], $d['program'], $d['year_level'], $d['section'],
                           $d['enrollment_status'], $d['date_enrolled'], $d['status'], $id]);
            flash('success', 'Student updated.');
            redirect('students.php');
        } catch (PDOException $ex) {
            if (!is_duplicate($ex)) throw $ex;
            $errors['student_no'] = 'Student number or email is already used by another record.';
        }
    }
}

$pageTitle = 'Edit Student | Student Management System';
$active = 'students';
$submitLabel = 'Save changes';
require __DIR__ . '/includes/header.php';
?>
<h2 class="h3 mb-0">Edit Student</h2>
<p class="text-body-secondary small mb-3">Dashboard &gt; Students &gt; Edit</p>
<?php require __DIR__ . '/includes/student_form.php'; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
