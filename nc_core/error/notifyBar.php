<?
$text = $_GET['text'];
$color = $_GET['color'];
echo '
<div id="lookhere" class="'. $color.'"><p>'. $text.'</p></div>
<script>
$("#lookhere").slideDown();
$("#lookhere").oneTime(10000, function() {
	$("#lookhere").slideUp();
});
</script>';
?>