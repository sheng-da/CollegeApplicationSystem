<?php
session_start();
if (isset($_SESSION['USER_ID'])) {
	session_unset();
	session_destroy();
}
header("Location: Login.php");
exit;
?>