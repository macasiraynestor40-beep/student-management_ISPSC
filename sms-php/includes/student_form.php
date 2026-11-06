<?php
// Expects: $d (field values), $errors (field => message), $submitLabel
function sf_input(string $key, string $label, array $d, array $errors, string $type = 'text', string $ph = ''): void { ?>
  <div class="col-sm-6 col-lg-4">
    <label class="form-label fw-semibold" for="<?= $key ?>"><?= e($label) ?></label>
    <input class="form-control<?= isset($errors[$key]) ? ' is-invalid' : '' ?>" id="<?= $key ?>" name="<?= $key ?>"
           type="<?= $type ?>" value="<?= e($d[$key] ?? '') ?>" placeholder="<?= e($ph) ?>">
    <?php if (isset($errors[$key])): ?><div class="text-danger small"><?= e($errors[$key]) ?></div><?php endif; ?>
  </div>
<?php }
function sf_select(string $key, string $label, array $options, array $d, array $errors): void { ?>
  <div class="col-sm-6 col-lg-4">
    <label class="form-label fw-semibold" for="<?= $key ?>"><?= e($label) ?></label>
    <select class="form-select<?= isset($errors[$key]) ? ' is-invalid' : '' ?>" id="<?= $key ?>" name="<?= $key ?>">
      <?php foreach ($options as $o): ?><option <?= ($d[$key] ?? '') === $o ? 'selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?>
    </select>
    <?php if (isset($errors[$key])): ?><div class="text-danger small"><?= e($errors[$key]) ?></div><?php endif; ?>
  </div>
<?php } ?>
<form method="post" class="card" novalidate>
  <div class="card-body">
    <?= csrf_field() ?>
    <div class="row g-3">
      <?php
      sf_input('student_no', 'Student No.', $d, $errors, 'text', '2025-0007');
      sf_input('first_name', 'First name', $d, $errors);
      sf_input('middle_name', 'Middle name', $d, $errors);
      sf_input('last_name', 'Last name', $d, $errors);
      sf_select('gender', 'Gender', GENDERS, $d, $errors);
      sf_input('email', 'Email', $d, $errors, 'email');
      sf_input('contact_no', 'Contact no.', $d, $errors, 'text', '09XXXXXXXXX');
      sf_select('program', 'Program', PROGRAMS, $d, $errors);
      sf_select('year_level', 'Year level', YEARS, $d, $errors);
      sf_input('section', 'Section', $d, $errors, 'text', '2-A');
      sf_select('enrollment_status', 'Enrollment status', ENROLLS, $d, $errors);
      sf_input('date_enrolled', 'Date enrolled', $d, $errors, 'date');
      sf_select('status', 'Status', STATUSES, $d, $errors);
      ?>
    </div>
    <div class="d-flex gap-2 mt-3">
      <button class="btn btn-primary" type="submit"><?= e($submitLabel) ?></button>
      <a class="btn btn-outline-secondary" href="students.php">Cancel</a>
    </div>
  </div>
</form>
