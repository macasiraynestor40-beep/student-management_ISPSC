<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$pdo = db();
$totalStudents = (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
$activeCount   = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE status = 'Active'")->fetchColumn();
$programCount  = (int)$pdo->query('SELECT COUNT(DISTINCT program) FROM students')->fetchColumn();
$irregularCount = (int)$pdo->query("SELECT COUNT(*) FROM students WHERE enrollment_status <> 'Regular'")->fetchColumn();
$userCount     = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();

$stats = [
  ['👥', '', $totalStudents,  'Total Students'],
  ['✅', '', $activeCount,    'Active Students'],
  ['🏛️', '', $programCount,   'Programs'],
  ['🔀', '',  $irregularCount, 'Irregular / Transferee'],
];

$pageTitle = 'Dashboard | Student Management System';
$active = 'dashboard';
require __DIR__ . '/includes/header.php';
?>
<h2 class="h3 mb-0">Dashboard</h2>
<p class="text-body-secondary small mb-3">Welcome back, <?= e($name) ?>!</p>

<div class="row g-3 mb-3">
  <?php foreach ($stats as [$icon, $color, $value, $label]): ?>
    <div class="col-sm-6 col-xl-3">
      <div class="card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-ico bg-<?= $color ?>-subtle"><?= $icon ?></div>
          <div>
            <div class="fs-2 fw-bold lh-1"><?= $value ?></div>
            <div class="small text-body-secondary"><?= e($label) ?></div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="alert alert-primary"><b>Welcome to the Student Management System.</b> Use the menu on the left to manage student records.</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header fw-semibold">Recent Announcements</div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between gap-3">
          <div><b>Enrollment Validation</b><br><small class="text-body-secondary">Please validate your enrollment records at the Registrar's Office.</small></div>
          <div class="date-chip bg-primary-subtle text-primary-emphasis">NOV<br>03</div>
        </li>
        <li class="list-group-item d-flex justify-content-between gap-3">
          <div><b>Finals Lab Activity</b><br><small class="text-body-secondary">Submit the Student Management System activity before the deadline.</small></div>
          <div class="date-chip bg-primary-subtle text-primary-emphasis">NOV<br>10</div>
        </li>
      </ul>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header fw-semibold">System Overview</div>
      <ul class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Academic Year</span><span>2026 - 2027</span></li>
        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Current Semester</span><span>1st Semester</span></li>
        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">Total Users</span><span><?= $userCount ?></span></li>
        <li class="list-group-item d-flex justify-content-between"><span class="text-body-secondary">System Status</span><span class="badge rounded-pill text-bg-success">Online</span></li>
      </ul>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>