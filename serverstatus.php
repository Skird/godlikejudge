<?
   include('security.php');
?>
<html>
<? 
   include('header.php'); 
      $result = 0;
      $dbstatus = 0;
      exec("ps -e | grep -i testing", $output, $result);
	  if (!empty($_POST['activate']) && $result) exec("cd data; ./testing >>testing.log 2>>testing.log &");
      if (!empty($_POST['deactivate']) && !$result) exec("killall testing");
      exec("ps -e | grep -i testing", $output, $result);
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
      </div>
   </body>
</html>
