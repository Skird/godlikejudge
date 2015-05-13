<?
   include("security.php");
?>
<html>
<? 
  
   include("header.php");
      $problem = $_GET['problem'];
      $num = $_GET['number'];
      $subm = $_GET['submission'];
      if (!$show_results) die("tokens is not avaliable");
      writeLog("$user used token for problem $problem");
      $token = (file_exists("data/tokens/${user}_${problem}.$num") ? (int) file_get_contents("data/tokens/{$user}_${problem}.$num"): -10000);
      if (check_admin($_SESSION['user'], $_SESSION['pass'])) $token = -10000;
      if (time() - $token < $token_regeneration ) die("you cant use this token");
      write_string("data/tokens/${user}_${problem}.$num", time());
      write_string("data/submissions/$subm.token", "available");
?>
      <div>Token is used.</div>
      <div><a href='exresults.php?num=<? echo $subm; ?>'>View results</a></div>
   </body>
</html>
