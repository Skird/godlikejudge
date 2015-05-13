<?
   include("security.php");
?>
<html>
<?
      include("header.php");
      if (empty($_POST['newpass']))
      {
?>
         <div>
            <form action='changepass.php' method='post'>
               <p>Old password: <input type='password' name='oldpass' size='30' maxlength='20' /></p>
               <p>New password: <input type='password' name='newpass' size='30' maxlength='20' /></p>
               <p>Confirmation: <input type='password' name='newpass2' size='30' maxlength='20' /></p>
               <p><input type='submit' value='Submit' /></p>
            </form>
         </div>
<?
      }
      else if ($_POST['newpass'] != $_POST['newpass2'])
      {
?>
         <div><p>Password and its confirmation do not match</p></div>
<?
      }                                          
      else if ($_POST['newpass'] == $_POST['newpass2'] && md5($_POST['oldpass']) != $_SESSION['pass'])
      {
?>
         <div><p>Incorrect old password</p></div>
<?
      }
      else
      {
         $users_cnt = file_get_contents("data/users");
         $arr = explode("\n", $users_cnt);
         for ($i = 0; $i < count($arr); $i++)
         {
            $info = explode("-", $arr[$i]);
            if (trim($info[0]) == trim($user))
               $info[2] = md5($_POST['newpass']);
            $arr[$i] = implode("-", $info);
         }
         $new_users = implode("\n", $arr);

         write_string("data/users", $new_users);
         session_destroy();
?>
         <div><p>Password was changed successfully</p></div>
         <div><a href='login.php'>Log in</a></div>
<?
      }
?>                  
</html>
