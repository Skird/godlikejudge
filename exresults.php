<?
   include("security.php");
   
   function printTokenInfo($user, $problem, $num, $subm)
   {
      global $token_regeneration;
      $token = file_exists("data/tokens/${user}_${problem}.$num") ? (int) file_get_contents("data/tokens/${user}_${problem}.$num") : -10000;
      if (time() - $token >= $token_regeneration)
         print "<div><a href='token.php?problem=$problem&number=$num&submission=$subm'>Use token $num</a></div>";
      else
      {
         print "<div>Token $num will be avaliable in ";
         print formatTime((int)($token_regeneration - time() + $token));
         print "</div>";
      }
   }
?>
<html>
<?
   include("header.php");

      $i = $_GET['num'];
      $admin = check_admin($_SESSION['user'], $_SESSION['pass']);
      $over = $contest_start + $contest_duration < time() ? 1 : 0;
      $partial = 0;
      if (!$admin && !$show_results && !$over)
      {
        $partial = 1;
       // die("");
      }

      if (!file_exists("data/submissions/$i.problem")) die("Cannot fetch problem of submission");
      $problem = trim(file_get_contents("data/submissions/$i.problem"));
      $stat = "";
      if (file_exists("data/submissions/$i.status")) $stat = trim(file_get_contents("data/submissions/$i.status"));
      if (file_exists("data/submissions/$i.compilationReport") && $stat == "CE")
      {
?>
         <textarea rows='4' cols='50' readonly>
            <? print file_get_contents("data/submissions/$i.compilationReport"); ?>
         </textarea>
<?
         die("");
      }

      if (!$over && !$admin && !file_exists("data/submissions/$i.token") && $show_results)
      {
         ?>
         <p>Use token to view results of this submission</p>
<?
         printTokenInfo($user, $problem, 1, $i);
         printTokenInfo($user, $problem, 2, $i);
         $partial = 1;
      }
      if (!$admin && !$over && !$partial)
      {
         $token_info = trim(file_get_contents("data/submissions/$i.token"));
         if ($token_info != "available") die("token is incorrect");
      }

      if ($over || $admin || file_exists("data/submissions/$i.user"))
      {
         $tuser = trim(file_get_contents("data/submissions/$i.user"));
         if ($tuser != trim($user) && !$admin) 
         {
            print "<div>This submission is not associated with this user</div>";
            die("");
         }
?>
         <p>
            Submission <b><? print $i; ?></b>, Problem <b><? print $pname[$problem]; ?></b>
<? 
            if (check_admin($user, $_SESSION['pass'])) 
            {
               print "<div>User: <b>";
               print getname($tuser); 
               print "</b></div>"; 
            }
?>
         </p>
<?
         

         if (file_exists("data/submissions/$i.result")) 
         {
?>
            <table>
               <tr>
                  <th>Test</th>
                  <th>Verdict</th>
                  <th>Time</th>
                  <th>Memory</th>
                  <th>Score</th>
               </tr>
<?
               $res = file_get_contents("data/submissions/$i.result");
               $tests = explode("\n", $res);
               $visible = array();
               if ($partial && file_exists("data/tasks/$problem/partial"))
               {
                  $v = file_get_contents("data/tasks/$problem/partial");
                  $visible = split(",", $v);
                //  foreach ($visible as $key => $value) echo $value , ", ";
                //  echo "<br>";
               }
               for ($i = 0; $i < count($tests); $i++)
               {
                  if (trim($tests[$i]) == "") continue;
                  $ok = 0;
                  foreach ($visible as $key => $value) if ((int)trim($value) == $i + 1) $ok = 1;
                  if (!$partial) $ok = 1;
                  if (!$ok) continue;
                  $info = explode(" ", $tests[$i]);
?>
                  <tr>
                     <td><? print $i+1; ?></td>
                     <td class='<? print $info[0]; ?>'><? print $info[0]; ?></td>
                     <td><? print (double) ($info[2]) / 1000.0; ?>s</td>
                     <td><? print $info[3]; ?> Kb</td>
                     <td><? if (!$partial) print $info[1]; else print "unknown"; ?></td>
                  </tr>
<?
               }
?>
            </table>
<?
      }
      else print "<div>Not checked yet</div>";
   }
?>
      <div><a href='results.php'>Back to results</a></div>
   </body>
</html>
