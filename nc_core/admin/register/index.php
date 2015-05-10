<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?
require_once('../../load.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Nebula Core Registration</title>
<link href="register.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
//Determine where to place the first box
$(document).ready(function() {
	$('#slidingDiv').css({ 
      left: function(value) {
        return parseFloat(value) + -2560 + parseFloat(parseFloat($(window).width() - 700) / 2);
      }
    });
});
//If the window is resized, recenter the current box
$(window).resize(function() {
	$('#slidingDiv').css({ 
      left: function(index, value) {
		var currentPosition = parseFloat($('#slidingDiv').css('left'))
		var currentSlide = parseFloat(-2560 + parseFloat(parseFloat($(window).width() - 700) / 2))
		if(currentPosition < 0)
			{
				return parseFloat(Math.ceil(currentPosition / 2560) * 2560) + currentSlide;
			}
			else
			{
        		return parseFloat(Math.ceil(currentPosition / 2560) * -2560) + currentSlide;
			}
      }
    });
});
//Progress to the next box
function nextSlide(){
    $('#slidingDiv').css({ 
      left: function(index, value) {
        return parseFloat(value) + -2560;
      }
    });
};
//Go to the last box
function lastSlide(){
    $('#slidingDiv').css({ 
      left: function(index, value) {
        return parseFloat(value) + 2560;
      }
    });
};
</script>
</head>
<body>
<div id="slidingDiv" class="animate">
<div class="contentBox" id="step1">
<?
$name = 'Registration: Step 1';
generalHeader($name);
?>
<form action="register_process.php" method="post" enctype="multipart/form-data" id="step1form">
<div style="float:left;max-width:310px;">
<p class="fancySelectHead">Name:</p>
<input type="text" name="name" maxlength="60" class="form">
<p class="fancySelectHead">Registration Code:</p>
<input type="text" name="code" maxlength="60" class="form">
</div>
<div id="step1Right">
<p>
So you'd like to become a member of Catserver, huh? Well there's a few important things you need to know:
</p>
<ul>
	<li>Membership is invite only. Sorry!</li>
	<li>Your Catserver.net login info does NOT apply to FTP or local storage accounts.</li>
	<li>I claim no responsibility for lost, deleted, or stolen files.</li>
	<li>Catserver is self-hosted, I don't gaurantee 100% uptime and accessibility.</li>
	<li>Your account can be removed or suspended at any time. If you've done something wrong, I'll let you know.</li>
</ul>
<p align="right"><input type="submit" name="submit" value="Continue" onclick="nextSlide();" class="submitButton"></p>
</div>
</form>
<script>
  /* attach a submit handler to the form */
  $("#step1form").submit(function(event) {

    /* stop form from submitting normally */
    event.preventDefault(); 
        
    /* get some values from elements on the page: */
    var $form = $( this ),
		code = $form.find( 'input[name="code"]' ).val()
        name = $form.find( 'input[name="name"]' ).val();

    /* Send the data using post and put the results in a div */
    $.post( 'register_process.php', { name: name, code: code },
      function( data ) {
          var content = $( data );
          $( "#result1" ).empty().append( content );
      }
    );
  });
</script>
</div>
<div class="contentBox" id="step2">
<?
$name = 'Step 2';
echo $name;
?>
<div id="result1"></div>
</div>
<div class="contentBox" id="step3">
<?
$name = 'Step 3';
echo $name;
?>
<div id="result2"></div>
</div>
<div class="contentBox" id="step4">
<?
$name = 'Registration Complete!';
echo $name;
?>
<div id="result3"></div>
</div>
</div>
</body>
</head>
</html> 