<?php require_once("admin-header.php");
if (!(isset($_SESSION[$OJ_NAME.'_'.'administrator']))){
	echo "<a href='../loginpage.php'>Please Login First!</a>";
	exit(1);
}?>
<?php if(isset($_POST['do'])){
	require_once("../include/check_post_key.php");
	$sql=$_POST['sql'];	
	echo $sql."<br />";
	echo pdo_query($sql)."***query finished!***";
	
}
?>
<div class="container">
<form action='db_modify.php' method=post>
	<b>Database Modify:</b><br />
	SQL:<input type=text size=40 name="sql" value=""><br />	
	<input type='hidden' name='do' value='do'>
	
	<?php require_once("../include/set_post_key.php");?>
	<input type=submit value='db_modify'>
</form>
</div>