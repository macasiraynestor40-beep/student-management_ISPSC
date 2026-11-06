<?php
require_once __DIR__ . '/includes/functions.php';
redirect(empty($_SESSION['uid']) ? 'login.php' : 'dashboard.php');
