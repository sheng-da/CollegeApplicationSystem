<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
	header("Location: Login.php");
	exit;
}

if (!isset($_POST['Step2'])) {
	header("Location: My_Applications.php");
	exit;
}

$_SESSION['first_name'] = $_POST['first_name'];
$_SESSION['last_name'] = $_POST['last_name'];
$_SESSION['date_of_birth'] = $_POST['date_of_birth'];
$_SESSION['street_address'] = $_POST['street_address'];
$_SESSION['city'] = $_POST['city'];
$_SESSION['state_id'] = $_POST['state_id'];
$_SESSION['country'] = $_POST['country'];
$_SESSION['zipcode'] = $_POST['zipcode'];
$_SESSION['phone_number'] = $_POST['phone_number'];
$_SESSION['US_Citizen'] = $_POST['US_Citizen'];
$_SESSION['native_English'] = $_POST['native_English'];
$_SESSION['gender'] = $_POST['gender'];
$_SESSION['veteran_status'] = $_POST['veteran_status'];
$_SESSION['military_branch'] = $_POST['military_branch'];
$_SESSION['hispanic_or_latino'] = $_POST['hispanic_or_latino'];
$_SESSION['race'] = $_POST['race'];

include 'start.php';
?>
 
<?php $pageTitle = "Application Information"; include 'header.php'; ?>
<h2>Application Information</h2>
<form method="POST" action="confirm.php">
	<p>
		Will you be applying for financial aid?
		<input type="radio" name="Financial_aid" value="Yes" /> Yes
		<input type="radio" name="Financial_aid" value="No" checked /> No
	</p>
	<p>
		Do you have employer tuition assistance?
		<input type="radio" name="Tuition_assistance" value="Yes" /> Yes
		<input type="radio" name="Tuition_assistance" value="No" checked /> No
	</p>
	<p>
		Are you also applying to other programs?
		<input type="radio" name="Other_program" value="Yes" /> Yes
		<input type="radio" name="Other_program" value="No" checked/> No
	</p>
	<p>
		Have you ever been convicted of a felony or a gross misdemeanor?
		<input type="radio" name="felony" value="Yes" /> Yes
		<input type="radio" name="felony" value="No" checked/> No
	</p>
	<p>
		Have you ever been placed on probation, suspended from, dismissed from or otherwise sanctioned by (for any period of time) any higher education institution?
		<input type="radio" name="School_incomplete" value="Yes" /> Yes
		<input type="radio" name="School_incomplete" value="No" checked/> No
	</p>
	<input type='submit' value='Next' name='Step3'/>
	<input type=reset value="Clear">
</form>
<?php include 'footer.php' ?>
