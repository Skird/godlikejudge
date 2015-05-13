<?
   session_start();
   include("checkuser.php");
   include("contest.php");
   include("utils.php");

   check_login();
   $isadmin = check_admin($_SESSION['user'], $_SESSION['pass']);

   $contest_dir = opendir("data/contests");
   $available = array();
   while (true == ($dir = readdir($contest_dir)))
      if ($dir != "." && $dir != "..")
         $available[count($available)] = $dir;
   sort($available);
?>
<html>
   <head>
      <link rel="stylesheet" type="text/css" href="styles.css" />
      <title>Godlike Judge</title>
   </head>
   <body>
      <div>
<?
                  
            if (!empty($_GET['contest']) && in_array($_GET['contest'], $available))
            {
               if (!check_access(trim($_GET['contest']), $_SESSION['user']) && !$isadmin) die("<div>No such contest, try again</div>");
               writeLog($_SESSION['user']." changes contest to ".$_GET['contest']);
               $_SESSION['contest'] = $_GET['contest'];
               print "<a href='index.php'>To the main page</a>";
            }
            else
            {
               if (!empty($_GET['contest'])) print "<div>No such contest, try again</div>";
?>
               <div>Current contest: <b><? echo empty($_SESSION['contest']) ? "Not chosen" : $_SESSION['contest']; ?></b></p></div>
<?       
               print "<ul>";
               foreach($available as $key => $value)
               {
               //   print (valueOfSetting($value, "access"));
               //   print "<br>";
                  $acc = valueOfSetting($value, "access");
                  if ((check_access(trim($value), $_SESSION['user'])) || $isadmin)
                  {
                     print "<li><a href='changecontest.php?contest=$value'";
                     if ($acc != "public") print " style='color: #FF0000'";
                     print ">$value</a>";
                   //  if ($acc != "public") print "</font>";
                     print "</li>";
                  }
               }
               print "</ul>";
            }
?>
      </div>
   </body>
</html>
