<?php
require_once '../includes/auth.php';
session_init();

logout();

header('Location: index.php');
exit;