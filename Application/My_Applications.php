<?php
session_start();

if (!isset($_SESSION['USER_ID'])) {
	header("Location: Login.php");
	exit;
}

include 'start.php';

$userId = $_SESSION['USER_ID'];
$userName = $_SESSION['USER_NAME'];

//include 'close.php';
?>

<?php $pageTitle = "My Applications"; include 'header.php'; ?>
<p1> Welcome <?php echo $userName; ?> <a href="Logout.php">Log out</a></p1>
<h1> My Applications </h1>
<div>
	<table border='1'>
		<tr>
			<th>App ID</th>
			<th>Student Type</th>
			<th>College</th>
			<th>Degree</th>
			<th>Major</th>
			<th>Term</th>
		</tr>
		<tr>
		<?php
			$sql = "SELECT
					A.APPLICATION_ID,
					T.STUDENT_TYPE_DESCRIPTION,
					C.COLLEGE_NAME,
					D.DEGREE_OF_APPLICATION_DESCRIPTION,
					M.MAJOR_OF_APPLICATION_DESCRIPTION,
					CONCAT(Te.TERM_QUARTER,' ',CAST(Te.TERM_YEAR AS CHAR(4))) AS Description
					FROM
					PersonalInformation P,
					Application A,
					StudentType T,
					College C,
					DegreeofApplication D,
					MajorofApplication M,
					Term Te
					WHERE
					P.APPLICANT_ID = A.APPLICANT_ID
					AND A.STUDENT_TYPE_ID = T.STUDENT_TYPE_ID
					AND A.COLLEGE_ID = C.COLLEGE_ID
					AND A.DEGREE_OF_APPLICATION_ID = D.DEGREE_OF_APPLICATION_ID
					AND A.MAJOR_OF_APPLICATION_ID = M.MAJOR_OF_APPLICATION_ID
					AND A.TERM_ID = Te.TERM_ID
					AND P.APPLICANT_USER_ID = ?";
			if ($stmt = mysqli_prepare($conn, $sql)) {
				mysqli_stmt_bind_param($stmt, "i", $userId);
				mysqli_stmt_execute($stmt);
				mysqli_stmt_bind_result($stmt, $appId, $type, $college, $degree, $major, $term);
				while (mysqli_stmt_fetch($stmt)){
					echo "<tr>\n";
					echo "<td><a href='confirm.php?applicationId=" . $appId ."'>" . $appId . "</a></td>\n";
					echo "<td>" . $type . "</td>\n";
					echo "<td>" . $college . "</td>\n";
					echo "<td>" . $degree . "</td>\n";
					echo "<td>" . $major . "</td>\n";
					echo "<td>" . $term . "</td>\n";
					echo "</tr>\n";
				}
				mysqli_stmt_close($stmt);
			}
		?>
		</tr>
	</table>
</div>
<form action="New Application.php">
	<input type="submit" value="Create New Application" />
</form>
<?php include 'footer.php' ?>