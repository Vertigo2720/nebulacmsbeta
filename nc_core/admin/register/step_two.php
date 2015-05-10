<?php
	//if(isset($_COOKIE['registration']))
	//{
		?>
		<form action="step_two_process.php" method="post">
		<div id="registerFormRight">
        <p class="fancySelectHead">Username:</p>
		<input type="text" name="username" maxlength="60" class="form" >
		<p class="fancySelectHead">Password:</p>
		<input type="password" name="pass" maxlength="24" class="form" >
		<p class="fancySelectHead">Confirm Password:</p>
		<input type="password" name="pass2" maxlength="24" class="form" >
        <p align="right"><input type="submit" name="submit" value="Finish" onclick="nextSlide();" class="submitButton"/></p>
        </div>
        <div id="registerFormLeft">
        <p><h1>Almost done!</h1></p>
        <p>We just need a little information from you before we can setup your account. Don't worry, this information won't be shared with anyone.</p>
		<p class="fancySelectHead">Name:</p>
		<input type="text" name="name" maxlength="60" class="form" >
		<p class="fancySelectHead">Email:</p>
		<input type="text" name="email" maxlength="60" class="form" >
        </div>
		</form>
	<?php
	//}
	//else
	//{
	//	echo 'Authentication failed. Begin registration process at http://catserver.net/register/';
	//}
	?>