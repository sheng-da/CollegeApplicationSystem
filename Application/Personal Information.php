<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
	header("Location: Login.php");
	exit;
}

if (!isset($_POST['Step1'])) {
	header("Location: My_Applications.php");
	exit;
}

$userId = $_SESSION['USER_ID'];

$_SESSION['student_type_id'] = $_POST['student_type'];
$_SESSION['college_id'] = $_POST['college'];
$_SESSION['degree_id'] = $_POST['degree'];
$_SESSION['major_id'] = $_POST['major'];
$_SESSION['term_id'] = $_POST['term'];

include 'start.php';

$sql = "SELECT APPLICANT_ID,
		APPLICANT_VETERAN_STATUS_ID,
		APPLICANT_MILITARY_BRANCH_ID,
		APPLICANT_FNAME,
		APPLICANT_LNAME,
		APPLICANT_PREFERRED_NAME,
		APPLICANT_DATE_OF_BIRTH,
		APPLICANT_STREET_ADDRESS,
		APPLICANT_CITY,
		APPLICANT_STATE_ID,
		APPLICANT_COUNTRY,
		APPLICANT_ZIPCODE,
		APPLICANT_PHONE_NUMBER,
		APPLICANT_US_CITIZEN,
		APPLICANT_EN_NATIVE,
		APPLICANT_GENDER,
		APPLICANT_HISPANIC_OR_LATINO
		FROM PersonalInformation
		WHERE APPLICANT_USER_ID = '$userId'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$count = mysqli_num_rows($result);
$raceArray[] = array();

if ($count == 1) {
	$id = $row['APPLICANT_ID'];
	$_SESSION['applicantId'] = $id; 
	$veteran_status_id = $row['APPLICANT_VETERAN_STATUS_ID'];
	$military_branch_id = $row['APPLICANT_MILITARY_BRANCH_ID'];
	$fname = $row['APPLICANT_FNAME'];
	$lname = $row['APPLICANT_LNAME'];
	$preferredName = $row['APPLICANT_PREFERRED_NAME'];
	$dateOfBirth = $row['APPLICANT_DATE_OF_BIRTH'];
	$streetAddr = $row['APPLICANT_STREET_ADDRESS'];
	$city = $row['APPLICANT_CITY'];
	$stateId = $row['APPLICANT_STATE_ID'];
	$country = $row['APPLICANT_COUNTRY'];
	$zipcode = $row['APPLICANT_ZIPCODE'];
	$phoneNum = $row['APPLICANT_PHONE_NUMBER'];
	$USCitizen = $row['APPLICANT_US_CITIZEN'];
	$EnNative = $row['APPLICANT_EN_NATIVE'];
	$gender = $row['APPLICANT_GENDER'];
	$hipanic = $row['APPLICANT_HISPANIC_OR_LATINO'];
	
	$sql = "SELECT RACE_TYPE_ID FROM Race WHERE APPLICANT_ID = '$id'";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_row($result)) {
		$raceArray[] = $row[0];
	}
}
?>

<?php $pageTitle = "Personal Information"; include 'header.php'; ?>
<h2>Personal Information</h2>
<form method="POST" action="Application Information.php">
	<p>
		First name:
		<input type="text" size=15 name="first_name" <?php echo 'value='.$fname; ?> >
	</p>
	<p>
		Last name:
		<input type="text" size=15 name="last_name" <?php echo 'value='.$lname; ?> >
	</p>
	<p>
		Date of Birth:
		<input type="date" name="date_of_birth" <?php echo 'value='.$dateOfBirth; ?> >
	</p>
	<p>
		Mailing Address:<br />
		Street Address: 
		<input type="text" size=15 name="street_address" <?php echo 'value='.$streetAddr; ?> > <br />
		City:
		<input type="text" size=15 name="city" <?php echo 'value='.$city; ?> > <br />
		State:
		<?php 
			$sql = "SELECT STATE_ID, STATE_DESCRIPTION FROM State";
			$result = mysqli_query($conn, $sql);
			$field_name = "state_id";
			$defaultValue = $stateId;
			include 'DDL.php';
		?> <br />
		Country:
		<input type="text" size=15 name="country" <?php echo 'value='.$country; ?> > <br />
		Zipcode:
		<input type="text" size=15 name="zipcode" <?php echo 'value='.$zipcode; ?> > <br />
	</p>
	<p>
		Preferred Phone number:
		<input type="text" size=15 name="phone_number" <?php echo 'value='.$phoneNum; ?> > <br />
	</p>
	<p>
		Are you a US Citizen?
		<input type="radio" name="US_Citizen" value="Yes" <?php if (isset($USCitizen) && $USCitizen == 'Yes') echo "checked";?> /> Yes
		<input type="radio" name="US_Citizen" value="No" <?php if (!isset($USCitizen) || $USCitizen == 'No') echo "checked";?> /> No
	</p>
	<p>
		Is English your native Language?
		<input type="radio" name="native_English" value="Yes" <?php if (isset($EnNative) && $EnNative == 'Yes') echo "checked";?> /> Yes
		<input type="radio" name="native_English" value="No" <?php if (!isset($EnNative) || $EnNative == 'No') echo "checked";?> /> No
	</p>
	<p>
		Gender?
		<input type="radio" name="gender" value="Male" <?php if (!isset($gender) || $gender == 'Male') echo "checked";?> /> Male
		<input type="radio" name="gender" value="Female" <?php if (isset($gender) && $gender == 'Female') echo "checked";?> /> Female
	</p>
	<p>
		Please tell us your veteran status:
		<?php 
		$sql = "SELECT VETERAN_STATUS_ID, VETERAN_STATUS_DESCRIPTION FROM VeteranStatus";
		$result = mysqli_query($conn, $sql);
		$defaultValue = $veteran_status_id;
		$field_name = "veteran_status";
		include 'DDL.php';
		?>
	</p>
	<p>
		Military Branch:
		<?php 
		$sql = "SELECT MILITARY_BRANCH_ID, MILITARY_BRANCH_DESCRIPTION FROM MilitaryBranch";
		$result = mysqli_query($conn, $sql);
		$field_name = "military_branch";
		include 'DDL.php';
		?>
	</p>
	<p>
		Are your Hispanic/Latino origin?
		<input type="radio" name="hispanic_or_latino" value="Yes" <?php if (isset($hipanic) && $hipanic == 'Yes') echo "checked";?> /> Yes
		<input type="radio" name="hispanic_or_latino" value="No" <?php if (!isset($EnNative) || $hipanic == 'No') echo "checked";?> /> No
	</p>
	<p>
		Please mark all that apply:<br />

		<?php 
		$sql = "SELECT RACE_TYPE_ID,RACE_TYPE_DESCRIPTION FROM RaceType;";
		$result = mysqli_query($conn, $sql);
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_row($result)) {
				if (in_array($row[0], $raceArray)) {
					echo "<input type='checkbox' name='race[]' value='" . $row[0] . "' checked>" . $row[1] . "</input></br>\n";
				}
				else {
					echo "<input type='checkbox' name='race[]' value='" . $row[0] . "'>" . $row[1] . "</input></br>\n";
				}
			}
		} else {
		echo "0 results";
		}
		?>
	</p>
	<input type='submit' value='Next' name='Step2'/>
	<input type=reset value="Clear">
</form>
<?php include 'close.php'; ?>
<?php include 'footer.php' ?>