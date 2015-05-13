 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" type="text/css" href="styles.css" />
   <title>Godlike Judge</title>
</head>

<?
   include("security.php");

   class User
   {
      public $username = "";
      public $score = "";
   }
   
   function short_name($a)
   {
      if (strlen($a) > 3) return substr($a, 0, 3);
      return $a;
   }

   function comp($a, $b)
   {
      if ($a->score < $b->score) return 1;
      else if ($a->score > $b->score) return -1;
      else return 0;
   }
?>

<html>
<?
//   include("header.php");
  
   if (!check_admin($user, $_SESSION['pass'])) die("access denied");
   if (empty($_POST['problems']) || empty($_POST['contests']))
   {
       ?>
        <form action = 'table.php' method = 'post'>
         Problems: <input type = 'text' name = 'problems' size = '80'> <br>
         Contests: <input type = 'text' name = 'contests' size = '80'> <br>
         <input type = 'submit' value = 'GO!'>
       </form>
       <?
   } 
      $active = array();
      $scores[""][""] = 0;
      $sum[""] = 0;
      $users_str = file_get_contents("data/users");
      $users = split("\n", $users_str);

      for ($i = 0; $i < count($users); $i++)
      {
         $info = split("-", $users[$i]);
         if (trim($info[0]) != "") $idents[] = trim($info[0]);
      }
      for ($i = 0; $i < count($idents); $i++)
         $sum[$idents[i]] = 0;

      $scnt = (int)file_get_contents("data/subm");
      $last[""][""] = 0;
       foreach($idents as $key => $value)
         foreach($pname as $pkey => $pvalue)
              $scores[$value][$pkey] = -1;
      $pname = array();
      $prb = split(",", $_POST['problems']);
      foreach ($prb as $value) $pname[trim($value)] = trim($value);
      $cnts = split(",", $_POST['contests']);
      $cname = array();
      foreach ($cnts as $value) $cname[trim($value)] = trim($value);
      for ($i = 0; $i < $scnt; $i++)
      {
         $author = file_get_contents("data/submissions/$i.user");
         $problem = file_get_contents("data/submissions/$i.problem");
         if ($pname[$problem] != $problem) continue;
      //   echo $author, " -> ", $problem, "<br>";
         $last[$author][$problem] = $i;
         $active[$author] = 1;
         $subm_time = (file_exists("data/submissions/$i.time")?(int)file_get_contents("data/submissions/$i.time"):0);
         $in_cont = (file_exists("data/submissions/$i.contest")?file_get_contents("data/submissions/$i.contest"):"null");
       //  echo ($in_cont!=$_SESSION['contest'])."<br>";
         $right_cont = $cname[$in_cont] == $in_cont;
         if (!file_exists("data/submissions/$i.token") || file_get_contents("data/submissions/$i.token") != "available"
              || !$right_cont) continue;
         $score = 0;
         if (file_exists("data/submissions/$i.score"))
            $score = (int)file_get_contents("data/submissions/$i.score");
         $scores[$author][$problem] = max($scores[$author][$problem], $score);      
      }
      foreach ($last as $author => $problems)
      foreach ($problems as $problem => $sid)
      {
         $score = 0;
         $subm_time = (file_exists("data/submissions/$sid.time")?(int)file_get_contents("data/submissions/$sid.time"):0);
         $in_cont = (file_exists("data/submissions/$sid.contest")?file_get_contents("data/submissions/$sid.contest"):"null");
         $right_cont = $cname[$in_cont] == $in_cont;
         if (file_exists("data/submissions/$sid.score") && $subm_time <= $contest_start + $contest_duration
              && $right_cont) 
            $score = (int) file_get_contents("data/submissions/$sid.score");
         $scores[$author][$problem] = max($scores[$author][$problem], $score);
      }

      foreach($idents as $key => $value)
         foreach($pname as $pkey => $pvalue)
            $sum[$value] += max(0,$scores[$value][$pkey]);
                    
      for ($i = 0; $i < count($idents); $i++)
      {
         $uinfo[$i] = new User;
         $uinfo[$i]->username = $idents[$i];
         $uinfo[$i]->score = $sum[$idents[$i]];
      }
      uasort($uinfo, "comp");
?>
      <table>
         <tr>
            <th><b>User</b></th>
<?
            foreach ($pname as $key => $value)
            {
            $curname = count($pname) >= 9 ? short_name($key) : $key;
               echo "<th title = '", $key, "'><b>", $curname, "</b></th>";
          }
?>
            <th>Sum</th>
         </tr>
<?
         foreach($uinfo as $key => $value)
         {
            $username = getname($value->username);
            $ident = $value->username;
            if (!$active[$ident] == 1) continue;
            if (check_admin2($ident)) continue;
?>
            <tr>
<?
               print "<td><b>$username</b></td>";
               foreach($pname as $key => $value000)
               {
                  print "<td>";
                  if ($scores[$ident][$key] != -1) print $scores[$ident][$key];
                  print "</td>";
               }
               print "<td>{$value->score}</td>";
?>
            </tr>
<?
         }
?>
      </table>
   </body>
</html>
