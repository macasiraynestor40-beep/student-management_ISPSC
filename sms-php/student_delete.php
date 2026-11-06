<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

// Deleting must be a POST so a link or image can't trigger it.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('students.php');
check_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    $st = db()->prepare('DELETE FROM students WHERE student_id = ?');
    $st->execute([$id]);
    flash($st->rowCount() ? 'success' : 'danger', $st->rowCount() ? 'Student deleted.' : 'Student not found.');
} else {
    flash('danger', 'Invalid student record.');
}
redirect('students.php');
