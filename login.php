<?
   include("checkuser.php");
  
   if (empty($_POST['user']))
   {
?>
      <form method='post' action='login.php'>
         <p>Handle: <input type='text' size='30' maxlength='20' name='user' /></p>
         <p>Password: <input type='password' size='30' maxlength='20' name='pass' /></p>
         <input type = 'submit' name = 'Log in' /> <br />
      </form>
<?
   }
   else
   {
      $user = $_POST['user'];
      $pass = md5($_POST['pass']);
      include ("utils.php");
      writeLog("$user logged in");
      session_start();
      $_SESSION['user'] = $user;
      $_SESSION['pass'] = $pass;
      check_login();
      print "<p><a href='changecontest.php'>Choose contest</a></p>";
   }
?>
