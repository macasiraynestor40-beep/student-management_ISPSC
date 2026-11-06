<?php
// Expects: $pageTitle (string), $active (dashboard|students|add)
require_once __DIR__ . '/functions.php';
require_login();
$flash = take_flash();
$name  = $_SESSION['name'] ?? 'User';
$nav = fn($key) => ($active ?? '') === $key ? ' active' : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'Student Management System') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">
  <script src="assets/theme.js"></script>
</head>

<body class="bg-body-tertiary">

  <aside class="sidebar" id="sidebar">
    <div class="brand">
      <div class="logo">🎓</div>
      <div>STUDENT<br>MANAGEMENT<br>SYSTEM</div>
    </div>
    <ul class="side-nav">
      <li><a class="<?= $nav('dashboard') ?>" href="dashboard.php"><span class="ico"></span>Dashboard</a></li>
      <li>
        <a class="<?= $nav('students') ?>" href="students.php"><span class="ico"></span>Students</a>
        <ul class="sub">
          <li><a href="students.php">Student List</a></li>
          <li><a href="student_add.php">Add Student</a></li>
        </ul>
      </li>
    </ul>
    <div class="side-logout"><a href="logout.php">Logout</a></div>
  </aside>

  <div class="main">
    <header class="d-flex align-items-center gap-3 px-3 px-lg-4 py-2 bg-body border-bottom sticky-top">
      <button class="btn btn-outline-secondary d-lg-none" id="menuBtn" type="button" aria-label="Toggle menu">☰</button>
      <h1 class="h6 flex-grow-1 mb-0 text-uppercase">Student Management System</h1>
      <div class="d-flex align-items-center gap-2">
        <div class="avatar"><?= e(strtoupper(mb_substr($name, 0, 1))) ?></div>
        <div class="d-none d-md-block lh-sm"><b><?= e($name) ?></b><small class="d-block text-body-secondary">Signed in</small></div>
        <a class="btn btn-outline-danger btn-sm ms-2" href="logout.php">Logout</a>
      </div>
    </header>

    <main class="content p-3 p-lg-4">
      <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash[0]) ?> alert-dismissible fade show" role="status">
          <?= e($flash[1]) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>