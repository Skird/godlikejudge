<?
   session_start();
   include("checkuser.php");
   include("contest.php");
   include("settings.php");
   include("utils.php");
   
   check_login();
   $user = $_SESSION['user'];
   
   $contest = trim($_SESSION['contest']);
   if (!file_exists("data/contests/$contest") || $contest == "")
   {
?>
      <a href='changecontest.php'>Choose contest</a>
<?
      die("");
   }
   loadContest($_SESSION['contest']);
?>
