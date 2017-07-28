<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
	header("Location: Login.php");
	exit;
}

include 'start.php';

if (isset($_POST['Step3']) && isset($_SESSION['first_name'])) {	
	if ($stmt = mysqli_prepare($conn,
		"SELECT CreateOrUpdateApplicant(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")){
		
		mysqli_stmt_bind_param($stmt, "iiissssssississss",
			$_SESSION['USER_ID'],
			$_SESSION['veteran_status'],
			$_SESSION['military_branch'],
			$_SESSION['first_name'],
			$_SESSION['last_name'],
			$_SESSION['preferred_name'],
			$_SESSION['date_of_birth'],
			$_SESSION['street_address'],
			$_SESSION['city'],
			$_SESSION['state_id'],
			$_SESSION['country'],
			$_SESSION['zipcode'],
			$_SESSION['phone_number'],
			$_SESSION['US_Citizen'],
			$_SESSION['native_English'],
			$_SESSION['gender'],
			$_SESSION['hispanic_or_latino']
		);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $applicantId);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	
	if (isset($applicantId)) {
		
		if ($stmt = mysqli_prepare($conn, "DELETE FROM Race WHERE APPLICANT_ID = ?")) {
			mysqli_stmt_bind_param($stmt, "i", $applicantId);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			for ($id = 0; $id < count($_SESSION['race']); $id++) {
				if ($stmt = mysqli_prepare($conn, "INSERT INTO Race(APPLICANT_ID, RACE_TYPE_ID) VALUES (?, ?)")) {
					mysqli_stmt_bind_param($stmt, "ii", $applicantId, $_SESSION['race'][$id]);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_close($stmt);
				}
			}
		}
		
		if ($stmt = mysqli_prepare($conn, "SELECT CreateApplication(?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?)")) {
			mysqli_stmt_bind_param($stmt, "iiiiiisssss",
				$applicantId,
				$_SESSION['student_type_id'],
				$_SESSION['college_id'],
				$_SESSION['degree_id'],
				$_SESSION['major_id'],
				$_SESSION['term_id'],
				$_POST['Financial_aid'],
				$_POST['Tuition_assistance'],
				$_POST['Other_program'],
				$_POST['felony'],
				$_POST['School_incomplete']
			);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $applicationId);
			mysqli_stmt_fetch($stmt);
			mysqli_stmt_close($stmt);
		}
	}
	$userId = $_SESSION['USER_ID'];
	session_unset();
	$_SESSION['USER_ID'] = $userId;
	
}
else if (isset($_GET['applicationId'])){
	$applicationId = $_GET['applicationId'];
	
}

if (!isset($applicationId)) {
	header("Location: My_Applications.php");
	exit;
}
else {
	$sql = "SELECT
			APPLICANT_ID,
			S.STUDENT_TYPE_DESCRIPTION,
			C.COLLEGE_NAME,
			D.DEGREE_OF_APPLICATION_DESCRIPTION,
			M.MAJOR_OF_APPLICATION_DESCRIPTION,
			CONCAT(T.TERM_QUARTER,' - ',CAST(T.TERM_YEAR AS CHAR(4))) AS Description,
			APPLICATION_FINITIAL_AID,
			APPLICATION_EMPLOYEE_ASSISTANCE,
			APPLICATION_MULTIPLE_PROGRAM,
			APPLICATION_FELONY_MISDEMEANOR,
			APPLICATION_INSTITUTION_IMCOMPLETE
			FROM
			Application A,
			StudentType S,
			College C,
			DegreeofApplication D,
			MajorofApplication M,
			Term T
			WHERE
			A.STUDENT_TYPE_ID = S.STUDENT_TYPE_ID
			AND A.COLLEGE_ID = C.COLLEGE_ID
			AND A.DEGREE_OF_APPLICATION_ID = D.DEGREE_OF_APPLICATION_ID
			AND A.MAJOR_OF_APPLICATION_ID = M.MAJOR_OF_APPLICATION_ID
			AND A.TERM_ID = T.TERM_ID
			AND A.APPLICATION_ID=?";		
	if ($stmt = mysqli_prepare($conn, $sql)) {
		mysqli_stmt_bind_param($stmt, "i", $applicationId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt,
								$applicantId,
								$studentType,
								$college,
								$degree,
								$majorO,
								$term,
								$appFinAid,
								$appEmpAssi,
								$appMulPro,
								$appFelMis,
								$appInsImc);
		
		mysqli_stmt_fetch($stmt);
		
		mysqli_stmt_close($stmt);
	}
	$result = $stmt;
	$sql = "SELECT
			V.VETERAN_STATUS_DESCRIPTION,
			M.MILITARY_BRANCH_DESCRIPTION,
			APPLICANT_FNAME,
			APPLICANT_LNAME,
			APPLICANT_PREFERRED_NAME,
			APPLICANT_DATE_OF_BIRTH,
			APPLICANT_STREET_ADDRESS,
			APPLICANT_CITY,
			S.STATE_DESCRIPTION,
			APPLICANT_COUNTRY,
			APPLICANT_ZIPCODE,
			APPLICANT_PHONE_NUMBER,
			APPLICANT_US_CITIZEN,
			APPLICANT_EN_NATIVE,
			APPLICANT_GENDER,
			APPLICANT_HISPANIC_OR_LATINO
			FROM
			PersonalInformation P,
			VeteranStatus V,
			MilitaryBranch M,
			State S
			WHERE
			P.APPLICANT_VETERAN_STATUS_ID = V.VETERAN_STATUS_ID
			AND P.APPLICANT_MILITARY_BRANCH_ID = M.MILITARY_BRANCH_ID
			AND P.APPLICANT_STATE_ID = S.STATE_ID
			AND APPLICANT_ID=?";
	if ($stmt = mysqli_prepare($conn, $sql)) {
		mysqli_stmt_bind_param($stmt, "i", $applicantId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $veteranStatus,
									$militaryBranch,
									$fname,
									$lname,
									$pname,
									$dateOfBirth,
									$streetAddr,
									$city,
									$state,
									$country,
									$zipcode,
									$phoneNum,
									$USCitizen,
									$EnNative,
									$gender,
									$hispanic);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
	}
	
	$sql = "SELECT
			T.RACE_TYPE_DESCRIPTION
			FROM
			Race R,
			RaceType T
			WHERE
			R.RACE_TYPE_ID = T.RACE_TYPE_ID
			AND R.APPLICANT_ID = ?";
	if ($stmt = mysqli_prepare($conn, $sql)) {
		mysqli_stmt_bind_param($stmt, "i", $applicantId);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $race);
		$raceArray = array();
		while (mysqli_stmt_fetch($stmt)){
			$raceArray[]=$race;
		}
		mysqli_stmt_close($stmt);
	}
}

?>


<?php $pageTitle = "Confirmation"; include 'header.php'; ?>
<h2>Confirmation Page</h2>
<div>
	<h4>Personal Information</h4>
	<p>
		<span style="margin-right: 30px;">First Name: <?php echo $fname;?></span>
		<span style="margin-right: 30px;">Last Name: <?php echo $lname;?></span>
		<span style="margin-right: 30px;">Preferred Name: <?php echo $pname;?></span>
	</p>
	<p>
		<span>Gender: <?php echo $gender;?></span> 
	</p>
	<p>
		<span>Date Of Birth: <?php echo $dateOfBirth;?></span> 
	</p>
	<p>
		<span>Street: <?php echo $streetAddr;?></span>
	</p>
	<p>
		<span>Phone Number: <?php echo $phoneNum;?></span>
	</p>
	<p>
		<span style="margin-right: 30px;">City: <?php echo $city;?></span>
		<span style="margin-right: 30px;">State: <?php echo $state;?></span>
		<span style="margin-right: 30px;">Country: <?php echo $country;?></span>
		<span style="margin-right: 30px;">Zipcode: <?php echo $zipcode;?></span>
	</p>
	<p>
		<span style="margin-right: 30px;">US Citizen: <?php echo $USCitizen;?></span>
		<span style="margin-right: 30px;">English Native: <?php echo $EnNative;?></span>
		<span style="margin-right: 30px;">Hispanic/Latino origin: <?php echo $hispanic;?></span>
	</p>
	<p>
		<span>Race:</span></br>
		<?php foreach($raceArray as $race){
			echo "<span style='margin-right: 30px;'>" . $race . "</span>\n";
		}?>
	</p>
	<p>
		<span style="margin-right: 30px;">Veteran Status: <?php echo $veteranStatus;?></span>
		<span style="margin-right: 30px;">Military Branch: <?php echo $militaryBranch;?></span>
	</p>
</div>
<div>
	<h4>Application Information</h4>
	<p>
		<span>Your student type: <?php echo $studentType;?></span>
	</p>
	<p>
		<span>College: <?php echo $college;?></span>
	</p>
	<p>
		<span>Degree: <?php echo $degree;?></span>
	</p>
	<p>
		<span>Major: <?php echo $majorO;?></span>
	</p>
	<p>
		<span>Term: <?php echo $term;?></span>
	</p>
	<p>
		<span>Apply for financial aid: <?php echo $appFinAid;?></span>
	</p>
	<p>
		<span>Have employer tuition assistance: <?php echo $appEmpAssi;?></span>
	</p>
	<p>
		<span>Apply to other program: <?php echo $appMulPro;?></span>
	</p>
	<p>
		<span>Have ever been ocnvicted of a felcony or a gross misdemeanor: <?php echo $appFelMis;?></span>
	</p>
	<p>
		<span>Have ever been sanctioned? <?php echo $appInsImc;?></span>
	</p>
</div>
<a href='My_Applications.php'>Back to Summary</a>
<?php include 'footer.php' ?>