<?
   include('security.php');
?>
<html>
<? 
   include('header.php'); 
      $result = 0;
      $dbstatus = 0;
      exec("ps -e | grep -i testing", $output, $result);
      exec("ps -e | grep -i dropbox", $output, $dbstatus);
      if (!empty($_POST['db_off']) && !$dbstatus) exec("python ../../bin/dropbox.py stop");
	  if (!empty($_POST['db_on']) && $dbstatus) exec("python ../../bin/dropbox.py start");
	  if (!empty($_POST['activate']) && $result) exec("cd data; ./testing >>testing.log 2>>testing.log &");
      if (!empty($_POST['deactivate']) && !$result) exec("killall testing");
      exec("ps -e | grep -i testing", $output, $result);
      exec("ps -e | grep -i dropbox", $output, $dbstatus);
?>
      <div class='panel'>
         <div>
            <p>Judge status: 
<?
            if (!$result) print "<font color='green'>active</font>";
            else print "<font color='red'>inactive</font>";
            print "</p>";
            if ($result && check_admin($_SESSION['user'], $_SESSION['pass']))
            {
?>                 
               <form action='serverstatus.php' method='post'>
                  <input type='submit' name='activate' value='Activate' />
               </form>
<?
            }
            if (!$result && check_admin($_SESSION['user'], $_SESSION['pass']))
            {
?>
               <form action='serverstatus.php' method='post'>
                  <input type='submit' name='deactivate' value='Deactivate' />
               </form>
<?
            }
?>

         </div>
         <div>
			 <p>Dropbox status:
<?
			if ($dbstatus) print "<font color='red'>inactive</font>";
			else print "<font color='green'>active</font>";
			print "</p>";
			if (!$dbstatus && check_admin($_SESSION['user'], $_SESSION['pass']))
			{
?>
               <form action='serverstatus.php' method='post'>
                  <input type='submit' name='db_off' value='Deactivate' />
               </form>
<?
			}
			if ($dbstatus && check_admin($_SESSION['user'], $_SESSION['pass']))
			{
?>
               <form action='serverstatus.php' method='post'>
                  <input type='submit' name='db_on' value='Activate' />
               </form>
<?
			}
?>
         </div>
      </div>
   </body>
</html>
