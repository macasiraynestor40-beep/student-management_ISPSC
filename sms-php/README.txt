STUDENT MANAGEMENT SYSTEM (plain PHP, one file per page)

Pages
  login.php / signup.php / logout.php   authentication
  dashboard.php                         dashboard (counts, announcements)
  students.php                          student list + search/filter (links to View / Edit / Delete)
  student_add.php                       add form
  student_view.php?id=N                 read-only details
  student_edit.php?id=N                 edit form (pre-filled)
  student_delete.php                    delete (POST only, then back to the list)

Shared code
  includes/config.php        DB_DRIVER ('mysql' or 'pgsql') and credentials
  includes/db.php            PDO connection + session
  includes/functions.php     validation, CSRF, flash messages, helpers
  includes/header.php        sidebar + top bar (also requires login)
  includes/footer.php        closing tags + scripts
  includes/student_form.php  form used by both Add and Edit
  assets/                    style.css, app.js, theme.js
  database/                  database_mysql.sql / database_pgsql.sql

Setup (XAMPP)
  1. Copy this folder to C:\xampp\htdocs\sms
  2. Start Apache + MySQL, import database/database_mysql.sql in phpMyAdmin
  3. Check includes/config.php, then open http://localhost/sms/login.php
