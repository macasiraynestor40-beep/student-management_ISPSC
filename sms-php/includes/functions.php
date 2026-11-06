<?php
require_once __DIR__ . '/db.php';

const PROGRAMS = ['BS Information Technology', 'BS Computer Science'];
const YEARS    = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
const ENROLLS  = ['Regular', 'Irregular', 'Transferee'];
const GENDERS  = ['Male', 'Female'];
const STATUSES = ['Active', 'Inactive'];

function e($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function is_duplicate(PDOException $ex): bool
{
    return in_array($ex->errorInfo[0] ?? '', ['23000', '23505'], true);
}

// ---- Authentication ----
function require_login(): void
{
    if (empty($_SESSION['uid'])) redirect('login.php');
}
function login_user(array $u): void
{
    session_regenerate_id(true);
    $_SESSION['uid']  = $u['user_id'];
    $_SESSION['name'] = $u['full_name'];
}

// ---- One-time flash messages ----
function flash(string $type, string $msg): void
{
    $_SESSION['flash'] = [$type, $msg];
}
function take_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

// ---- CSRF protection for every POST form ----
function csrf_token(): string
{
    return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function check_csrf(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)($_POST['csrf'] ?? ''))) {
        http_response_code(419);
        exit('Invalid or expired form. Go back, refresh the page and try again.');
    }
}

// ---- Student data ----
function student_input(array $src): array
{
    $t = fn($k) => trim((string)($src[$k] ?? ''));
    return [
        'student_no' => $t('student_no'),
        'first_name' => $t('first_name'),
        'middle_name' => $t('middle_name'),
        'last_name' => $t('last_name'),
        'gender' => $t('gender'),
        'email' => strtolower($t('email')),
        'contact_no' => $t('contact_no'),
        'program' => $t('program'),
        'year_level' => $t('year_level'),
        'section' => $t('section'),
        'enrollment_status' => $t('enrollment_status'),
        'date_enrolled' => $t('date_enrolled'),
        'status' => $t('status') ?: 'Active',
    ];
}

function validate_student(array $d): array
{
    $err = [];
    if (!preg_match('/^\d{4}-\d{4}$/', $d['student_no'])) $err['student_no'] = 'Use the format 2025-0007.';
    if ($d['first_name'] === '') $err['first_name'] = 'Required.';
    if ($d['last_name'] === '')  $err['last_name']  = 'Required.';
    if (!in_array($d['gender'], GENDERS, true)) $err['gender'] = 'Pick a gender.';
    if (!filter_var($d['email'], FILTER_VALIDATE_EMAIL)) $err['email'] = 'Enter a valid email.';
    if (!preg_match('/^09\d{9}$/', $d['contact_no'])) $err['contact_no'] = 'Use an 11-digit number starting with 09.';
    if (!in_array($d['program'], PROGRAMS, true)) $err['program'] = 'Pick a program.';
    if (!in_array($d['year_level'], YEARS, true)) $err['year_level'] = 'Pick a year level.';
    if ($d['section'] === '') $err['section'] = 'Required.';
    if (!in_array($d['enrollment_status'], ENROLLS, true)) $err['enrollment_status'] = 'Pick an enrollment status.';
    $dt = DateTime::createFromFormat('Y-m-d', $d['date_enrolled']);
    if (!$dt || $dt->format('Y-m-d') !== $d['date_enrolled']) $err['date_enrolled'] = 'Pick a valid date.';
    if (!in_array($d['status'], STATUSES, true)) $err['status'] = 'Pick Active or Inactive.';
    return $err;
}

function find_student(int $id): ?array
{
    $st = db()->prepare('SELECT * FROM students WHERE student_id = ?');
    $st->execute([$id]);
    return $st->fetch() ?: null;
}

function badge(string $v): string
{
    $c = ['Active' => 'success', 'Regular' => 'success', 'Inactive' => 'danger', 'Irregular' => 'warning', 'Transferee' => 'warning'];
    return '<span class="badge rounded-pill text-bg-' . ($c[$v] ?? 'secondary') . '">' . e($v) . '</span>';
}
