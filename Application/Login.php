<?php
session_start();

if (isset($_SESSION['USER_ID'])) {
	header("Location: My_Applications.php");
	exit;
}

$error = false;

if (isset($_POST['login'])) {
	
	include 'start.php';
	
	$username = trim($_POST['username']);
	$username = strip_tags($username);
	$username = htmlspecialchars($username);
	
	$password = trim($_POST['password']);
	$password = strip_tags($password);
	$password = htmlspecialchars($password);
	
	if (empty($username)){
		$error = true;
		$nameError = "Please enter a valid username";
	}
	
	if (empty($password)){
		$error = true;
		$passError = "Please enter your password";
	}
	
	if (!$error) {
		
		$password = md5($password);
		
		$sql = "SELECT USER_ID, USER_NAME, PASSWORD FROM UserMd5 WHERE USER_NAME='$username'";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_array($result);
		$count = mysqli_num_rows($result);
		
		if ($count == 1 && $row['PASSWORD']==$password)
		{
			$_SESSION['USER_ID'] = $row['USER_ID'];
			$_SESSION['USER_NAME'] = $row['USER_NAME'];
			include 'close.php';
			header("Location: My_Applications.php");
		}
		else {
			$errMSG = "Incorrect Credentials.";
		}
	}
	
	include 'close.php';
}

?>

<?php $pageTitle = "Application Login"; include 'header.php'; ?>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
<?php
if (isset($errMSG)){
	echo "<p>" . $errMSG . "</p>";
}
?>
<p>
	username:
	<input type="text" size=15 name="username">
	<?php echo $nameError;?>
</p>
<p>
	password:
	<input type="password" size=15 name="password">
	<?php echo $passError;?>
</p>
<input type='submit' value='log in' name='login' />
<a href="Create_Account.php">Create New Account</a>
</form>
<?php include 'footer.php' ?>