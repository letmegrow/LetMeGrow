<?php /* Template Name: CustomPage-testFunc
 * Created by Marwah

 */ ?>


<?php

get_header();


list($cmsmasters_layout) = kids_theme_page_layout_scheme();


echo '<!--_________________________ Start Content _________________________ -->' . "\n";?>

<html>
<head></head>
<body class="page_bg">


<?php



$conn = new mysqli('localhost', 'root', 'bitnami', 'letmegrowdb') 
or die ('Cannot connect to db');?>

<!-- ******************************************************************************************* -->
<h3>Do you have a plant in your mind?</h3>
<strong>Check if you can plant it, or how about you let us give you suggestions</strong>
<form method="post" ><table border="0">
	<tr><th>Postcode:</th> <th><input name="postcode" type="text" value="<?php $varPostcode;?>" required /><th>
	</tr>
	<tr>
	<th>VegetableName: </th> <th><?php


		$result = $conn->query("SELECT Name from plantinfo ORDER by plantinfo.Name ASC");
		
		echo "<html>";
		echo "<body>";
		echo "<select name='Name'>";
		echo '<option value="GiveMeSuggestion">nothing in my mind</option>';
		while ($row = $result->fetch_assoc()) {

					  unset( $Name);
					  $Name = $row['Name']; 
					  echo '<option value="'.$Name.'">'.$Name.'</option>';
					 
	}

		echo "</select>";
		echo "</body>";
		echo "</html>";
	?></tr></th>
	<tr>
	<th>Month:</th><th><select name="monthName" size='1'>
		<?php
		for ($i = 0	; $i < 12; $i++) {
			$time = strtotime(sprintf('%d months', $i));   
			$monthName = date('F', $time);   
			$value = date('n', $time);
			echo "<option value='$monthName'>$monthName</option>";	
		}
		?>
	</select></th>
	</tr>
	<tr>
	<th><input type="submit" name="formSubmit" value="Can i plant it?" /></th>
	<th><input type="submit" name="formSubmitGiveSugg" value="Give my suggestions" /></th>
	</tr>
</table>
</form>

<!-- test the plant --!>
<?php
if( isset($_POST['formSubmit']) )
{
	if(isset($_POST['postcode'])){ $varPostcode = $_POST['postcode']; }
	if(isset($_POST['Name'])){ $varName = $_POST['Name']; }
	if(isset($_POST['monthName'])){ $varMonthName = $_POST['monthName']; }
	
	$sql1 = "select postcodesuburb.postcode from postcodesuburb where postcodesuburb.postcode = " . $varPostcode . " or postcodesuburb.suburb = '" . $varPostcode . "' ;";
	$result1 = $conn->query($sql1);
	
	
	if ($result1->num_rows == 0) {
		echo "Enter valid postcode or suburb name ";
		echo "Note: Our website can test the probability of planting in VIC and NSW"; 
	}
	else if ($result1->num_rows > 0){
		$sql2 = "select * from vegetabelclimatemonth where vegetabelclimatemonth.VegetableName LIKE '%" . $varName . "%' and vegetabelclimatemonth.month = '" . $varMonthName . "' and vegetabelclimatemonth.ClimateZone like (select climatezones.ClimateZone from climatezones where climatezones.Postcode = (select postcodesuburb.postcode from postcodesuburb where postcodesuburb.postcode = " . $varPostcode . " or postcodesuburb.suburb = '" . $varPostcode . "'));";
		$result2 = $conn->query($sql2);

		if ($result2->num_rows > 0 and $varName != 'nothing in my mind') {
			echo "$varName can be planted on $varMonthName in the location you chose $varPostcode "; 
		} else {
			if ($varName == 'nothing in my mind')
			{
							echo "It is unlikely for $varName to survive on $varMonthName in the location you chose $varPostcode ".'<br>';
			}
			
			//****** give suggestions if user cannot plant their chosen plant
			$sql3 = "select vegetabelclimatemonth.VegetableName from vegetabelclimatemonth where  vegetabelclimatemonth.month = '" . $varMonthName . "' and vegetabelclimatemonth.ClimateZone like (select climatezones.ClimateZone from climatezones where climatezones.Postcode = (select postcodesuburb.postcode from postcodesuburb where postcodesuburb.postcode = " . $varPostcode . " or postcodesuburb.suburb = " . $varPostcode . "));";
			$result3 = $conn->query($sql3);

			if ($result3->num_rows == 0) {
				echo "Unfortunately, I cant give you any suggestions for this location $varPostcode, at this time of the year ($varMonthName)."; 
			}else {
					echo "Here is a list of what you can plant".'<br>';
					while ($row = $result3->fetch_object()) {
					foreach ($row as $r){
						echo $r.'<br>';
						/*$sql4 = "select guid from wp_letmegrow2posts where post_title like '%". $r ."%'";
						$result4 = $conn->query($sql4);
						while ($row2 = $result4->fetch_object()) {
						foreach ($row2 as $r2){
							echo '<a href="'. $r2 .'">More Info</a> <br>';
							}
						}*/
					}
				}
			}
		}
		
	}


	$conn->close();
}


?>

<!-- give sugistions --!>

<?php
if( isset($_POST['formSubmitGiveSugg']) )
{
	if(isset($_POST['postcode'])){ $varPostcode = $_POST['postcode']; }
	if(isset($_POST['Name'])){ $varName = $_POST['Name']; }
	if(isset($_POST['monthName'])){ $varMonthName = $_POST['monthName']; }
	
	$sql1 = "select postcodesuburb.postcode from postcodesuburb where postcodesuburb.postcode = " . $varPostcode . " or postcodesuburb.suburb = '" . $varPostcode . "' ;";
	$result1 = $conn->query($sql1);
	
	
	if ($result1->num_rows == 0) {
		echo "Enter valid postcode or suburb name ";
		echo "Note: Our website can test the probability of planting in VIC and NSW"; 
	}
	else if ($result1->num_rows > 0){
		//****** give suggestions if user cannot plant their chosen plant
			$sql3 = "select vegetabelclimatemonth.VegetableName from vegetabelclimatemonth where  vegetabelclimatemonth.month = '" . $varMonthName . "' and vegetabelclimatemonth.ClimateZone like (select climatezones.ClimateZone from climatezones where climatezones.Postcode = (select postcodesuburb.postcode from postcodesuburb where postcodesuburb.postcode = " . $varPostcode . " or postcodesuburb.suburb = " . $varPostcode . "));";
			$result3 = $conn->query($sql3);

			if ($result3->num_rows == 0) {
				echo "Unfortunately, I cant give you any suggestions for this location $varPostcode, at this time of the year ($varMonthName)."; 
			}else {
					echo "Here is a list of what you can plant".'<br>';
					while ($row = $result3->fetch_object()) {
					foreach ($row as $r){
						echo $r.'<br>';
						
						/*$sql4 = "select guid from wp_letmegrow2posts where post_title like '%". $r ."%'";
						$result4 = $conn->query($sql4);
						while ($row2 = $result4->fetch_object()) {
						foreach ($row2 as $r2){
							echo '<a href="'. $r2 .'">More Info</a> <br>';
						}
					}*/
				}
			}
		}
		
		
	}


	$conn->close();
}


?>





<!-- *******************************************************************************************
</body>
</html>


<?php
'<!-- _________________________ Finish Content _________________________ -->' . "\n\n";


if ($cmsmasters_layout == 'r_sidebar') {
	echo "\n" . '<!-- _________________________ Start Sidebar _________________________ -->' . "\n" . 
	'<div class="sidebar" role="complementary">' . "\n";
	
	get_sidebar();
	
	echo "\n" . '</div>' . "\n" . 
	'<!-- _________________________ Finish Sidebar _________________________ -->' . "\n";
} elseif ($cmsmasters_layout == 'l_sidebar') {
	echo "\n" . '<!-- _________________________ Start Sidebar _________________________ -->' . "\n" . 
	'<div class="sidebar fl" role="complementary">' . "\n";
	
	get_sidebar();
	
	echo "\n" . '</div>' . "\n" . 
	'<!-- _________________________ Finish Sidebar _________________________ -->' . "\n";
}


get_footer();

?>