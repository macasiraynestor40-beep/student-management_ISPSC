<?php
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['uid'])) redirect('dashboard.php');

$err = [];
$name = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $name    = trim((string)($_POST['name'] ?? ''));
  $email   = strtolower(trim((string)($_POST['email'] ?? '')));
  $pass    = (string)($_POST['password'] ?? '');
  $confirm = (string)($_POST['confirm'] ?? '');

  if (mb_strlen($name) < 2) $err['name'] = 'Enter your full name.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $err['email'] = 'Enter a valid email address.';
  if (strlen($pass) < 8) $err['password'] = 'Use at least 8 characters.';
  elseif (!preg_match('/[A-Za-z]/', $pass) || !preg_match('/\d/', $pass)) $err['password'] = 'Include at least one letter and one number.';
  if ($pass !== $confirm) $err['confirm'] = "Passwords don't match.";

  if (!$err) {
    try {
      db()->prepare('INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)')
        ->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
      $st = db()->prepare('SELECT user_id, full_name FROM users WHERE email = ?');
      $st->execute([$email]);
      login_user($st->fetch());
      redirect('dashboard.php');
    } catch (PDOException $ex) {
      if (is_duplicate($ex)) $err['email'] = 'That email is already registered.';
      else throw $ex;
    }
  }
}
function fe(array $err, string $k): string
{
  return isset($err[$k]) ? '<div class="text-danger small mt-1">' . e($err[$k]) . '</div>' : '';
}
function ic(array $err, string $k): string
{
  return isset($err[$k]) ? ' is-invalid' : '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign up | Student Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="assets/theme.js"></script>
</head>

<body class="bg-body-tertiary">
  <main class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
    <form method="post" class="card shadow-sm w-100" style="max-width:420px" novalidate>
      <div class="card-body p-4">
        <h1 class="h4 mb-4">Create your account</h1>
        <?= csrf_field() ?>
        <div class="mb-3">
          <label for="name" class="form-label fw-semibold">Full name</label>
          <input id="name" name="name" class="form-control<?= ic($err, 'name') ?>" autocomplete="name" value="<?= e($name) ?>">
          <?= fe($err, 'name') ?>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input id="email" name="email" type="email" class="form-control<?= ic($err, 'email') ?>" autocomplete="email" value="<?= e($email) ?>">
          <?= fe($err, 'email') ?>
        </div>
        <div class="mb-3">
          <label for="pass" class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <input id="pass" name="password" type="password" class="form-control<?= ic($err, 'password') ?>" autocomplete="new-password">
            <button type="button" class="btn btn-outline-secondary toggle" data-target="pass">Show</button>
          </div>
          <div class="form-text">At least 8 characters with a letter and a number.</div>
          <?= fe($err, 'password') ?>
        </div>
        <div class="mb-3">
          <label for="confirm" class="form-label fw-semibold">Confirm password</label>
          <div class="input-group">
            <input id="confirm" name="confirm" type="password" class="form-control<?= ic($err, 'confirm') ?>" autocomplete="new-password">
            <button type="button" class="btn btn-outline-secondary toggle" data-target="confirm">Show</button>
          </div>
          <?= fe($err, 'confirm') ?>
        </div>
        <button type="submit" class="btn btn-primary w-100">Create account</button>
        <p class="small text-body-secondary mt-3 mb-0">Already have an account? <a href="login.php">Sign in</a></p>
      </div>
    </form>
  </main>
  <script src="assets/app.js"></script>
</body>

</html>