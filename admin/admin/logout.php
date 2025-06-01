<?php
session_start();
session_destroy();
header("Location: admin_login.php"); // or admin_login.php if admin
exit();
