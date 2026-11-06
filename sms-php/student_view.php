<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$s = find_student((int)($_GET['id'] ?? 0));
if (!$s) { flash('danger', 'Student not found.'); redirect('students.php'); }

$fullName = trim($s['first_name'] . ' ' . ($s['middle_name'] ?? '') . ' ' . $s['last_name']);
$details = [
    'Student ID' => $s['student_id'], 'Student No.' => $s['student_no'], 'Full Name' => $fullName,
    'Gender' => $s['gender'], 'Email' => $s['email'], 'Contact No.' => $s['contact_no'],
    'Program' => $s['program'], 'Year Level' => $s['year_level'], 'Section' => $s['section'],
    'Enrollment Status' => $s['enrollment_status'], 'Date Enrolled' => $s['date_enrolled'], 'Status' => $s['status'],
];

$pageTitle = 'Student Details | Student Management System';
$active = 'students';
require __DIR__ . '/includes/header.php';
?>
<h2 class="h3 mb-0">Student Details</h2>
<p class="text-body-secondary small mb-3">Dashboard &gt; Students &gt; View</p>
<div class="card"><div class="card-body">
  <div class="row g-3">
    <?php foreach ($details as $label => $value): ?>
    <div class="col-sm-6 col-md-4">
      <div class="p-2 rounded bg-body-tertiary h-100" style="overflow-wrap:anywhere">
        <small class="d-block text-body-secondary mb-1"><?= e($label) ?></small>
        <b><?= e($value !== null && $value !== '' ? $value : '—') ?></b>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="d-flex gap-2 mt-3">
    <a class="btn btn-primary" href="student_edit.php?id=<?= (int)$s['student_id'] ?>">Edit</a>
    <a class="btn btn-outline-secondary" href="students.php">Back to list</a>
  </div>
</div></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
