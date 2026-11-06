<?php
require_once __DIR__ . '/includes/functions.php';
if (!empty($_SESSION['uid'])) redirect('dashboard.php');

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $email = strtolower(trim((string)($_POST['email'] ?? '')));
  $pass  = (string)($_POST['password'] ?? '');
  if ($email === '' || $pass === '') {
    $error = 'Enter your email and password.';
  } else {
    $st = db()->prepare('SELECT user_id, full_name, password_hash FROM users WHERE email = ?');
    $st->execute([$email]);
    $u = $st->fetch();
    if (!$u || !password_verify($pass, $u['password_hash'])) {
      $error = 'Wrong email or password.';
    } else {
      login_user($u);
      redirect('dashboard.php');
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in | Student Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="assets/theme.js"></script>
</head>

<body class="bg-body-tertiary">
  <main class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
    <form method="post" class="card shadow-sm w-100" style="max-width:400px" novalidate>
      <div class="card-body p-4">
        <h1 class="h4 mb-4">Sign in</h1>
        <?= csrf_field() ?>
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input id="email" name="email" type="email" class="form-control" autocomplete="email" value="<?= e($email) ?>">
        </div>
        <div class="mb-3">
          <label for="pass" class="form-label fw-semibold">Password</label>
          <div class="input-group">
            <input id="pass" name="password" type="password" class="form-control" autocomplete="current-password">
            <button type="button" class="btn btn-outline-secondary toggle" data-target="pass">Show</button>
          </div>
        </div>
        <div class="text-danger small mb-2" role="alert"><?= e($error) ?></div>
        <button type="submit" class="btn btn-primary w-100">Sign in</button>
        <p class="small text-body-secondary mt-3 mb-0">No account yet? <a href="signup.php">Create one</a></p>
      </div>
    </form>
  </main>
  <script src="assets/app.js"></script>
</body>

</html>