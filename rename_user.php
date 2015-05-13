<?
   include("security.php");
?>
<html>
<?
      include("header.php");  
      if (!check_admin($_SESSION['user'], $_SESSION['pass'])) die("incorrect query");
      if (empty($_POST['oldname']))
      {
?>
         <div>
            <form action='rename_user.php' method='post'>
               <p>Old name: <input type='text' name='oldname' size='30' maxlength='30' /></p>
               <p>New name: <input type='text' name='newname' size='30' maxlength='30' /></p>
               <p><input type='submit' value='Submit' /></p>
            </form>
         </div>
<?
      }
      else
      {
         $users_cnt = file_get_contents("data/users");
         $arr = explode("\n", $users_cnt);
         for ($i = 0; $i < count($arr); $i++)
         {
            $info = explode("-", $arr[$i]);
            if (trim($info[0]) == trim($_POST['oldname']))
               $info[0] = trim($_POST['newname']);
            $arr[$i] = implode("-", $info);
         }
         $new_users = implode("\n", $arr); 
         write_string("data/users", $new_users);
         $subm = (int)file_get_contents("data/subm");
         for ($i = 0; $i < $subm; $i++)
         {
            if (file_exists("data/submissions/$i.user") 
                && trim(file_get_contents("data/submissions/$i.user")) == trim($_POST['oldname']))
                write_string("data/submissions/$i.user", trim($_POST['newname']));
         }
?>
         <div><p>Password changed successfully</p></div>
         <div><a href='login.php'>Log in</a></div>
<?
      }
?>                  
</html>
