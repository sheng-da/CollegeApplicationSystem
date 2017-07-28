<?php
if (mysqli_num_rows($result) > 0) {

	echo "<select name='" . $field_name . "'>\n";

	while($row = mysqli_fetch_row($result)) {
		if ($row[0] == $defaultValue) {
			echo "<option selected='selected' value='" . $row[0] . "'>" . $row[1] . "</option>\n";
		}
		else {
			echo "<option value='" . $row[0] . "'>" . $row[1] . "</option>\n";
		}
}
echo "</select>\n";
} else {
echo "0 results";
}
?>
