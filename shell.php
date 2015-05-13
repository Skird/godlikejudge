<?
	include('security.php');
?>
<html>
	<?
		include('header.php');
		if (!check_admin($_SESSION['user'], $_SESSION['pass']))
			die("You do not have access to this page");
		if (!empty($_POST['execute']))
			exec($_POST['command']);
	?>
	<div class='panel'>
		<form action='shell.php' method='post'>
			<input type='text' name='command' size='50'/>
			<input type='submit' name='execute' value='Execute' />
		</form>
	</div>
	<div class='panel'>
		<div>
			shell.log
			<a href='shell.php'>Refresh</a>
		</div>
		<div>
			<textarea readonly rows='30' cols='140' style='font-size: 10;'><? 
				print htmlspecialchars(file_get_contents("data/shell.log")); 
			?></textarea>
		</div>
	</div>
</html>
