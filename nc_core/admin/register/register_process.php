<?php
require_once('../../load.php');

if(!empty($_POST['code']) & !empty($_POST['name']))
{
	$name = $_POST['name'];
	$result = database("SELECT * from `nc_secure_register` WHERE `name` = $name");
	if(mysqli_num_rows($result) == 0)
	{
		$code = rand();
	}
	else
	{
	$i = 0;
	$id = mysql_result($result,$i,"id");
	$name = mysql_result($result,$i,"name");
	$code = mysql_result($result,$i,"code");
	}
	if ($code == $_POST['code'])
	{
		//$hour = time() + 3600;
		//setcookie("registration", $_POST['name'], $hour, '/');
		//$query2="DELETE FROM register WHERE id = '". $id."'";
		//mysql_query($query2);
		?>
        <span id="step2Text">
        <p><h1>Jacked up and good to go!</h1></p>
        <p>Please note: For security reasons, if registration is interrupted beyond this point you may need to request a new registration code.</p>
        <script>
		$.ajax({
		  url: 'step_two.php',
		  cache: false,
		  success: function(data) {
			$('#result2').html(data);
		  }
		});
		</script>
        <p><button onclick="nextSlide();" class="submitButton">Continue</button></p>
        </span>
        <?
	}
	else
	{
		print_r($result);
		echo '<span id="step2Text"><h1>Sorry, you\'re not in the database.</h1><p>If you were given a code and already attempted registration without success, please contact me: victor@catserver.net.</p><p><button onclick="lastSlide();" class="submitButton">Back</button></p></span>';
	}
}
else
{
	echo '<span id="step2Text"><h1>Uh oh, looks like you forgot a field!</h1><p><button onclick="lastSlide();" class="submitButton">Back</button></p></span>';
}
?>