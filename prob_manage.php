<?
   include("security.php");
   if (!check_admin($_SESSION['user'], $_SESSION['pass'])) die("You have not access for this page");
   include("header.php");
   if (trim($_POST['problem']) == "" && trim($_GET['problem']) == "")
   {
      $problems_dir = opendir("data/tasks");
       $drs = array();
       $cnt0 = 0;
      while (true == ($dir = readdir($problems_dir)))
        if ($dir != "." && $dir != "..")
        {
           $drs[$cnt0++] = $dir;
           //echo "<a onclick='addOne(\"$dir, \")'>$dir</a> ";
        }
      sort($drs);
      print "<div class = panel>";
      for ($i = 0; $i < $cnt0; $i++) echo "<a href='prob_manage.php?problem={$drs[$i]}'>{$drs[$i]}</a> ";
      print "</div>";
      die("");
   } 
   else
   {
      if (trim($_POST['problem'] != ""))
      {
          $problem = trim($_POST['problem']);
      } else
      {
          $problem = trim($_GET['problem']);
      }
   }

   $ch_ext = "";
   if (file_exists("data/tasks/$problem/checker.cpp")) $ch_ext = ".cpp";
   if (file_exists("data/tasks/$problem/checker.dpr")) $ch_ext = ".dpr";
   if (file_exists("data/tasks/$problem/checker.pas")) $ch_ext = ".pas";
   //(file_exists("data/tasks/$problem/checker")) $ch_ext = "";



   if ($_POST['save'] == "doit")
   {
      //$problem = $_POST['problem'];
      $tl = (int)trim($_POST['timelimit']);
      $ml = (int)trim($_POST['memorylimit']);
      $check = $_POST['check'];
      $grade = $_POST['grade']; 
      $doall = $_POST['doall']; 


      $partial = $_POST['partial']; 
      write_string("data/tasks/$problem/timelimit", $tl);
      write_string("data/tasks/$problem/memorylimit", $ml);
      write_string("data/tasks/$problem/partial", $partial);
      write_string("data/tasks/$problem/checker$ch_ext", $check);
      write_string("data/tasks/$problem/grade.cpp", $grade);
      write_string("data/tasks/$problem/doall.sh", $doall);


      if ($_POST['compile'] == "on")
      {
         exec("cd data/tasks/$problem; sed s/\\\\r// doall.sh > doall2.sh");   
         exec("cd data/tasks/$problem; cp doall2.sh doall.sh");
         exec("cd data/tasks/$problem; rm doall2.sh");
         exec("cd data/tasks/$problem; bash doall.sh 2>compile.log"); 
      }
   }
   echo "<div class = 'panel'>";
   echo "<p><b>Problem $problem</b> <a href = 'prob_manage.php'>Choose problem</a></p>";
   echo "<form action = 'prob_manage.php'  method = 'post'>";
   echo "<input type = 'hidden' name = 'save' value = 'doit'>";
   echo "<input type = 'hidden' name = 'problem' value = '$problem'>";
   echo "<p>Time Limit <input type = 'text' name = 'timelimit' value = '", file_get_contents("data/tasks/$problem/timelimit"), "'></p>";
   echo "<p>Memory Limit <input type = 'text' name = 'memorylimit' value = '", file_get_contents("data/tasks/$problem/memorylimit"), "'></p>";
   echo "<p>Partial feedback <input type = 'text' name = 'partial' value = '", file_get_contents("data/tasks/$problem/partial"), "'></p>";
   echo "<p>Checker ($ch_ext):</p>";
   echo "<p><textarea cols = 50 rows = 10 name = 'check'>", file_get_contents("data/tasks/$problem/checker$ch_ext"), "</textarea></p>";
   echo "<p>Grader (cpp):</p>";
   echo "<p><textarea cols = 50 rows = 10 name = 'grade'>", file_get_contents("data/tasks/$problem/grade.cpp"), "</textarea></p>";
   echo "<p>doall.sh:</p>";
   echo "<p><textarea cols = 50 rows = 10 name = 'doall'>", file_get_contents("data/tasks/$problem/doall.sh"), "</textarea></p>";
   
   echo "<p>Compilation log:</p>";
   echo "<p><textarea cols = 50 rows = 5 name = 'log' readonly>", file_get_contents("data/tasks/$problem/compile.log"), "</textarea></p>";
   echo "<p>Compile: <input type = checkbox name = 'compile'></p>";
   
   echo "<p><input type = 'submit'  value = 'Save'></p>";
   echo "</form>";
?>
