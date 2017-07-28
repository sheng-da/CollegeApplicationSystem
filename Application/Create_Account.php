<?php
session_start();

if (isset($_SESSION['USER_ID'])) {
	header("Location: My_Applications.php");
	exit;
}

$error = false;

if (isset($_POST['register'])) {
	
	include 'start.php';
	
	$username = trim($_POST['username']);
	$username = strip_tags($username);
	$username = htmlspecialchars($username);
	
	$password = trim($_POST['password']);
	$password = strip_tags($password);
	$password = htmlspecialchars($password);
	
	$repassword = trim($_POST['repassword']);
	$repassword = strip_tags($repassword);
	$repassword = htmlspecialchars($repassword);
	
	if (empty($username)){
		$error = true;
		$nameError = "username is not valid: $username";
	}
	else {
		$sql = "SELECT * FROM UserMd5 WHERE USER_NAME='$username'";
		$result = mysqli_query($conn, $sql);
		$count = mysqli_num_rows($result);
		if ($count != 0) {
			$error = true;
			$nameError = "username exists";
		}
		mysqli_free_result($result);
	}
	
	if (empty($password)){
		$error = true;
		$passError = "password is not valid";
	}
	else if (strcmp($password, $repassword) != 0) {
		$error = true;
		$repassError = "password does not match";
	}
	
	$password = md5($password);
	
	if (!$error){
		if ($stmt = mysqli_prepare($conn, "INSERT INTO UserMd5(USER_NAME, PASSWORD) VALUES(?, ?)")){
			mysqli_stmt_bind_param($stmt, "ss", $username, $password);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			
			$errTyp = "success";
			$errMSG = "Successfully registered." . "<a href='Login.php'>Back to login</a>";
			unset($username);
			unset($password);
			unset($repassword);
		}
		else {
			$errTyp = "danger";
			$errMSG = "Something went wrong";
		}
	}
	mysqli_close($conn);
}
?>

<?php
$pageTitle = "Create New Account";
include 'header.php';
?>
<h1>Create New Account</h1>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
	<?php
		if (isset($errMSG)) {
			echo ($errType=="success") ? "success: " : "$errTyp: ";
			echo "$errMSG </br>";
		}
	?>
	<p>
		username:
		<input type="text" size=15 name="username">
		<?php echo $nameError; ?>
	</p>
	<p>
		password:
		<input type="password" size=15 name="password">
		<?php echo $passError; ?>
	</p>
	<p>
		Re-enter password:
		<input type="password" size=15 name="repassword">
		<?php echo $repassError; ?>
	</p>
	<input type='submit' name='register' value='Submit' />
</form>
<?php include 'footer.php' ?>