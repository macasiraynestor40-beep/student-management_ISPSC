<?php
require_once __DIR__ . '/includes/functions.php';
require_login();

$q       = trim((string)($_GET['q'] ?? ''));
$program = (string)($_GET['program'] ?? '');
$enroll  = (string)($_GET['enroll'] ?? '');
$status  = (string)($_GET['status'] ?? '');

$where = [];
$params = [];
if ($q !== '') {
  $like = '%' . strtolower($q) . '%';
  $where[] = "(LOWER(student_no) LIKE ? OR LOWER(first_name) LIKE ? OR LOWER(COALESCE(middle_name,'')) LIKE ?
                 OR LOWER(last_name) LIKE ? OR LOWER(COALESCE(email,'')) LIKE ?)";
  array_push($params, $like, $like, $like, $like, $like);
}
if ($program !== '') {
  $where[] = 'program = ?';
  $params[] = $program;
}
if ($enroll  !== '') {
  $where[] = 'enrollment_status = ?';
  $params[] = $enroll;
}
if ($status  !== '') {
  $where[] = 'status = ?';
  $params[] = $status;
}

$sql = 'SELECT * FROM students' . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY student_id';
$st = db()->prepare($sql);
$st->execute($params);
$rows = $st->fetchAll();
$all  = (int)db()->query('SELECT COUNT(*) FROM students')->fetchColumn();

$pageTitle = 'Student List | Student Management System';
$active = 'students';
require __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
  <div>
    <h2 class="h3 mb-0">Student List</h2>
    <p class="text-body-secondary small mb-3">Dashboard &gt; Students &gt; Student List</p>
  </div>
  <a class="btn btn-primary" href="student_add.php">+ Add Student</a>
</div>

<div class="card">
  <div class="card-body">
    <form method="get" class="d-flex flex-wrap gap-2 mb-3">
      <input type="search" name="q" class="form-control" style="flex:1 1 200px" value="<?= e($q) ?>" placeholder="Search by name, student no., email..." aria-label="Search students">
      <select name="program" class="form-select w-auto" aria-label="Filter by program">
        <option value="">All Programs</option>
        <?php foreach (PROGRAMS as $p): ?><option <?= $p === $program ? 'selected' : '' ?>><?= e($p) ?></option><?php endforeach; ?>
      </select>
      <select name="enroll" class="form-select w-auto" aria-label="Filter by enrollment">
        <option value="">All Enrollment</option>
        <?php foreach (ENROLLS as $o): ?><option <?= $o === $enroll ? 'selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?>
      </select>
      <select name="status" class="form-select w-auto" aria-label="Filter by status">
        <option value="">All Status</option>
        <?php foreach (STATUSES as $o): ?><option <?= $o === $status ? 'selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?>
      </select>
      <button class="btn btn-primary" type="submit">Search</button>
      <a class="btn btn-outline-primary" href="students.php">Reset</a>
    </form>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-nowrap mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Student No.</th>
            <th>Full Name</th>
            <th>Program</th>
            <th>Year / Section</th>
            <th>Enrollment</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $s): ?>
            <tr>
              <td><?= (int)$s['student_id'] ?></td>
              <td><?= e($s['student_no']) ?></td>
              <td><b><?= e($s['first_name']) ?> <?= $s['middle_name'] ? e(mb_substr($s['middle_name'], 0, 1)) . '.' : '' ?> <?= e($s['last_name']) ?></b>
                <small class="d-block text-body-secondary"><?= e($s['email']) ?></small>
              </td>
              <td><?= e($s['program']) ?></td>
              <td><?= e($s['year_level']) ?> / <?= e($s['section']) ?></td>
              <td><?= badge($s['enrollment_status']) ?></td>
              <td><?= badge($s['status']) ?></td>
              <td>
                <div class="btn-group btn-group-sm">
                  <a class="btn btn-outline-secondary" href="student_view.php?id=<?= (int)$s['student_id'] ?>">View</a>
                  <a class="btn btn-outline-secondary" href="student_edit.php?id=<?= (int)$s['student_id'] ?>">Edit</a>
                  <form method="post" action="student_delete.php" data-confirm="Delete this student?" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int)$s['student_id'] ?>">
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-0 rounded-end">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$rows): ?><tr>
              <td colspan="8" class="text-center text-body-secondary py-4">No students found.</td>
            </tr><?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="small text-body-secondary mt-3">Showing <?= count($rows) ?> of <?= $all ?> entries</div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>