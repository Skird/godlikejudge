<?
   function short_name($a)
   {
      if (strlen($a) > 4) return substr($a, 0, 3);
      return $a;
   }
   include("security.php");
   $subm = (int) file_get_contents("data/subm");
   $pscore[""] = "null"; 
   $cnt = 0;
   $upper = $subm; 
   $show_other_users = 0;
   $admin = check_admin($user, $_SESSION['pass']);
   $over = $contest_start + $contest_duration < time() ? 1 : 0;
   $before = time() < $contest_start;
  
   if ($_POST['rejudge'])
   {
      $id = $_POST['id'];
      exec("cd data; ./judge $id >>testing.log 2>>testing.log & ");
?>
      <html>
         <head>
            <script language='javascript'>
<? 
               print "document.location.href = 'results.php?allusers={$_GET['allusers']}&allcont={$_GET['allcont']}'"; 
?>
            </script>
         </head>
      </html>
<?
      die("");
   }
   if ($admin && !empty($_GET['allusers'])) $show_other_users = 1;
?>
<html>
<?
   include("header.php");
?>
   <table>
      <tr>
         <th>Token #</th>
<?
         foreach ($pname as $problem => $name)
         {                      
            $curname = count($pname) >= 9 ? short_name($problem) : $problem;
            echo "<th title='$problem'>",$curname,"</th>";
         }
?>
      </tr>
<?
      $user = $_SESSION['user'];
      for ($i = 1; $i <= 2; $i++)
      {
         print "<tr><td>$i</td>";
         foreach ($pname as $problem => $name)
         {
            $token = file_exists("data/tokens/${user}_${problem}.$i") ? (int) file_get_contents("data/tokens/${user}_${problem}.$i") : -10000;
            if (time() - $token >= $token_regeneration) echo "<td>", (count($pname)>=10?"OK":"Avaliable"), "</td>";
            else 
            {
               print "<td>Will be available in ";
               print formatTime((int)($token_regeneration - time() + $token));
               print "</td>";
            }
         }
         print "</tr>";
      }
?>
   </table>
   <br>
      <table>
         <tr>
            <th>#</th>
            <? if ($show_other_users) print "<th><b>User</b></th>"; ?>
            <th><b>Problem</b></th>
            <th><b>Result</b></th>
            <th><b>Feedback</b></th>
            <th><b>Code</b></th>
            <? if ($admin) print "<th><b>Rejudge</b></th>"; ?>
         </tr>
<?
         $cnt = 0;
         for ($i = $subm - 1; $i >= 0; $i--)
         {
			if (!file_exists("data/submissions/$i.status")) continue;
            if (file_exists("data/submissions/$i.user"))
            {
               $tuser = trim(file_get_contents("data/submissions/$i.user"));
               if ($tuser != trim($user) && !$show_other_users) continue;
            }
            $problem = trim(file_get_contents("data/submissions/$i.problem"));
            if ($pname[$problem] != $problem && !($admin && $_GET['allcont'])) continue;
           // if ($cnt >= $upper) break;
            $cnt++;
?>
            <tr>
               <td><? print $i; ?></td>
<? 
               if ($show_other_users) 
               {
                  print "<td>";
                  print getname($tuser);
                  print "</td>"; 
               }
?>
               <td>
<? 
               if ($pname[$problem] == "") print "<font color='green'>"; 
               print $problem; 
               if ($pname[$problem] == "") print "</font>"; 
?>
            </td>
<?
               if (file_exists("data/submissions/$i.score")) 
               {
                  $score = (int) file_get_contents("data/submissions/$i.score");
                  if ($over || $admin || $show_scores && file_exists("data/submissions/$i.token")) 
                  {
					  $stat = trim(file_get_contents("data/submissions/$i.status"));
					  if ($stat == "CE") print "<td>CE</td>";
					  else print "<td><b>$score</b></td>"; 
				  }
                  else 
                  {
                     $stat = trim(file_get_contents("data/submissions/$i.status"));
                     if ($stat == "CE") print "<td>CE</td>";                    
                     else print "<td>Judged</td>";
                  }
               
                  $pscore[$problem] = max($pscore[$problem], (int) $score);
                  /*if ($over || $show_results || $admin)*/ print "<td><a href='exresults.php?user=$user&num=$i'>View feedback</a></td>";
                 // else print "<td>Feedback is not available</td>";
               }
               else
               {
                  print "<td>";
                  if (file_exists("data/submissions/$i.status"))
                  {
                     $stat = trim(file_get_contents("data/submissions/$i.status"));
                     if ($stat == "testing") print "Testing in progress...";
                     else if ($stat == "waiting") print "In queue...";
                     else print $stat; 
                  }
                  else print "Not judged yet";
                  print "</td>";
                     
                  print "<td>";
                  if ($stat == "CE") print "<a href='exresults.php?user=$user&num=$i'>View CE report</a>";
                  else print "Report is not available";
                  print "</td>";
               }
?>
               <td>
<?
                  if (($show_codes || check_admin($user, $_SESSION['pass']))) 
                     print "<a href='code.php?user=$user&num=$i'>View source</a>";    
                  else print "Source is not available";
?>
               </td>
<?
            if ($admin)
            {
               $formName = "rejudge$i";
?>
               <td>
                  <div>
                     <form action='<? print $_SERVER["REQUEST_URI"]; ?>' method='post' id='<? print $formName; ?>'>
                        <input type='hidden' name='id' value='<? print $i; ?>' />
                        <input type='hidden' name='rejudge' value='Rejudge!' />
                        <a onclick='document.getElementById("<? print $formName; ?>").submit();'>Rejudge!</a>
                     </form>
                  </div>
               </td>
<?
            }
?>
            </tr>
<?
         }
?>
      </table>
     
<?  
      if (check_admin($user, $_SESSION['pass']))
      {
         echo " <a href='results.php?allusers=1&allcont=",$_GET['allcont'],"'>Show all submissions</a>";
         echo " <a href='results.php?allusers=",$_GET['allusers'],"&allcont=1'>Show all contests</a>";

      }
?>
   </body>
</html>
