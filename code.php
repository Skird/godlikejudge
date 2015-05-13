<?
	include ("security.php");
?>
<html>
	
<?
	include("header.php");
  
		if (!$show_codes)
		{
?>
			<div>Codes are hidden</div>
<?
			die("");
		}
  
		$i = $_GET['num'];
		if (file_exists("data/submissions/$i.user"))
		{
			$tuser = trim(file_get_contents("data/submissions/$i.user"));
			if (trim($tuser) != trim($user) && !check_admin($user, $_SESSION['pass'])) 
			{
?>
				<p>This submission is not associated with this user</p>
<?
				die("");
			}
			$problem = trim(file_get_contents("data/submissions/$i.problem"));
?>
			<p>Submission <b>#<? print $i; ?></b>, Problem <b><? print $pname[$problem]; ?></b></p>
<?
			if (check_admin($user, $_SESSION['pass'])) 
			{
				print "<p>User: <b>";
				print getname($tuser);
				print "</b></p>";
			}
			$code = file_get_contents("data/submissions/$i.code");
?>
			<textarea rows='20' cols='100' readonly><? print $code; ?></textarea>
<?
	}  
?>
	</body>
</html>
