<?
   include("security.php");
?>
<html>
<?
    include("header.php");
    #echo (string)$contest_start."  ".(string)$contest_duration."  ";
      $before = ($contest_start > time() || $contest_start + $contest_duration <= time());
      #echo $before."<br>";
     // echo ($start_time)." i ". (time())."<br>".$duration."   ";
      if (check_admin($user, $_SESSION['pass'])) {$before = 0; $show_statements = 1;}
     if ($before || !$show_statements) die("Statements are not available");
?>
      <div>
         <b>Problems</b>:
         <?
            foreach($pname as $key => $value)
               print "<a href='statement.php?problem=$key&show=1'>$value</a> ";
         ?>
      </div>
<?
      if (!empty($_GET['show']))
      {
         $prb = $_GET['problem'];
         if (empty($pname[$prb])) die("No such problem");
         if (!file_exists("data/tasks/$prb/statement.htm")) die("Statement is constantly missing you");
         writeLog("$user watches $prb");
?>
         <div>
            <div>[<? echo $prb; ?>], <b><? echo $pname[$prb]; ?></b> <br>
            <b>TL</b>=<? print file_get_contents("data/tasks/$prb/timelimit") ?>ms <b>ML</b>=<? print file_get_contents("data/tasks/$prb/memorylimit"); ?>Kb</div>
            <div><? print file_get_contents("data/tasks/$prb/statement.htm"); ?></div>
         </div>
         <div>
         <table>
           <tr>
           <th>Input</th>
           <th>Output</th>
           </tr>
<?
        if (file_exists("data/tasks/$prb/partial"))
        {
           $arr = split(",", file_get_contents("data/tasks/$prb/partial"));
           foreach ($arr as $key => $value)
           {
               $k = (int)$value;
               print "<tr><td><textarea rows = 3 cols = 20 readonly>";
              
               $f = "";
              // print $f;
               if ((int)($k/100) == 0) $f = $f . (string)"0";
               else $f = $f . (string)((int)($k/100));
               
               if ((int)($k/10)%10 == 0) $f = $f . (string)"0";
               else $f = $f . (string)((int)($k/10)%10);
               
               if ((int)($k)%10 == 0) $f = $f."0";
               else $f = $f.(string)((int)($k)%10); 
               
               print file_get_contents("data/tasks/$prb/tests/$f");
               print "</textarea></td><td><textarea rows = 3 cols = 20>";
               print file_get_contents("data/tasks/$prb/tests/$f.ans");
               print "</textarea></td></tr>";
           } 
        }
    
?>
         </table>
         </div>
<?
      }
?>
   </body>
</html>
