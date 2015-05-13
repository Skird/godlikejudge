<?
   include("security.php");
   $admin = check_admin($_SESSION['user'], $_SESSION['pass']);
?>
<html>
<?
   include("header.php");

      if (!$admin && !$enable_submit)
      {
         print "<div>Cannot submit now</div></body>";
         die("");
      }
      if (!$admin && $contest_start + $contest_duration - time() < 0)
      {
         print "<div>Contest is over</div>";
         die("");
      }
      if (!$admin && $contest_start > time())
      {
         print "<div>Contest is not started</div>";
         die("");
      }
      $problem = trim($_POST['problem']);
      $lang = addslashes($_POST['lang']);
      $code = $_POST['code'];
      $langname['cpp'] = "GNU C++";
      $langname['pascal'] = "Free Pascal";
      $langname['java'] = "Java 7";
      $username = getname($user);
      
      $ok = 0;
      foreach($pname as $key => $value)
         if (trim($key) == trim($problem)) $ok = 1;
      if ($ok == 0) die("there is no such problem");
      writeLog("$user sends solution for $problem");
?>
      <div>
         <div>User: <b><? print $username; ?></b></div>
         <div>Problem: <b><? print $pname[$problem]; ?></b></div>
         <div>Language: <b><? print $langname[$lang]; ?></b></div>
         <div>Code:</div>
         <textarea rows='20' cols='100' name='code' readonly><? print $_POST['code']; ?></textarea>
         <div>Solution submitted</div>
         <div><a href='results.php'>View results</a></div>
      </div>
<?
      $num = (int) file_get_contents("data/subm");
      write_string("data/subm", $num + 1);
      write_string("data/submissions/$num.code", $code);
      write_string("data/submissions/$num.user", $user);
      write_string("data/submissions/$num.problem", $problem);
      write_string("data/submissions/$num.language", $lang);
      write_string("data/submissions/$num.status", "waiting");
      write_string("data/submissions/$num.time", time());
      write_string("data/submissions/$num.contest", $_SESSION['contest']);
?>
   </body>
</html>
