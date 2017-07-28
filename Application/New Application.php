<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
	header("Location: Login.php");
	exit;
}

include 'start.php';
?>

<?php $pageTitle = "New Application"; include 'header.php'; ?>
<h2>New Application</h2>
<form method="POST" action="Personal Information.php">
	<p>
		What type of Student are you:
		<?php 
			$sql = "SELECT STUDENT_TYPE_ID, STUDENT_TYPE_DESCRIPTION FROM StudentType";
			$result = mysqli_query($conn, $sql);
			$field_name = "student_type";
			include 'DDL.php';
		?>
	</p>
	<p>
		Which College are you applying to: 
		<?php 
			$sql = "SELECT COLLEGE_ID, COLLEGE_NAME FROM College";
			$result = mysqli_query($conn, $sql);
			$field_name = "college";
			include 'DDL.php';
		?>
	</p>
	<p>
		What type of degree are you applying for:
		<?php 
			$sql = "SELECT DEGREE_OF_APPLICATION_ID, DEGREE_OF_APPLICATION_DESCRIPTION FROM DegreeofApplication";
			$result = mysqli_query($conn, $sql);
			$field_name = "degree";
			include 'DDL.php';
		?>
	</p>
	<p>
		Please select the Major you are apply to:
		<?php 
			$sql = "SELECT MAJOR_OF_APPLICATION_ID, MAJOR_OF_APPLICATION_DESCRIPTION FROM MajorofApplication";
			$result = mysqli_query($conn, $sql);
			$field_name = "major";
			include 'DDL.php';
		?>	
	</p>
	<p>
		Please select the Term you are apply to:
		<?php 
			$sql = "SELECT TERM_ID, CONCAT(TERM_QUARTER,\" - \",CAST(TERM_YEAR AS CHAR(4))) AS Description FROM Term";
			$result = mysqli_query($conn, $sql);
			$field_name = "term";
			include 'DDL.php';
		?>	
	</p>
	<input type='submit' value='Next' name="Step1"/>
	<input type=reset value="Clear">
</form>
<?php include 'close.php'; ?>
<?php include 'footer.php' ?>