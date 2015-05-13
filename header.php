<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <link rel="stylesheet" type="text/css" href="styles.css" />
   <title>Godlike Judge []</title>
</head>
<body>
   <div>
      <div>
         User: <b><? echo getname($user); ?></b>
         <a href='logout.php'>Log out</a>
      </div>
      <div>
        Current contest: <b><? print $_SESSION['contest']; ?></b>
<? 
        if (time() < $contest_start) print "[before]"; 
        else if (time() > $contest_start + $contest_duration) print "[over]";
        else print "[running]";
?>
      </div>
      <div>
         Time elapsed: [<? echo (int)((time() - $contest_start) / 60); ?>m <? echo (time() - $contest_start) % 60; ?>s]
      </div>
      <div>
         Time left: [<? echo (int)(($contest_start + $contest_duration - time()) / 60); ?>m <? echo ($contest_start + $contest_duration - time()) % 60; ?>s]
      </div>
   </div>
   <table>
      <tr>
         <td class='menuoption'><a href='index.php'>Submit</a></td>
         <td class='menuoption'><a href='monitor.php'>Monitor</a></td> 
         <td class='menuoption'><a href='results.php'>Results</a></td>
         <td class='menuoption'><a href='statement.php'>Statements</a></td>
         <td class='menuoption'><a href='clar.php'>Request clarification</a></td>
         <td class='menuoption'><a href='clars.php'>Clarifications</a></td>
         <td class='menuoption'><a href='changecontest.php'>Change contest</a></td>
         <td class='menuoption'><a href='changepass.php'>Change password</a></td>
         <td class='menuoption'><a href='serverstatus.php'>Server status</a></td>
<?
         if (check_admin($user, $_SESSION['pass']))
            print "<td class='menuoption'><a href='manage.php'>Set up contests</a>*</td>";
         if (check_admin($user, $_SESSION['pass']))
            print "<td class='menuoption'><a href='prob_manage.php'>Set up problems</a>*</td>";
?>
         
<?
         if (check_admin($user, $_SESSION['pass']))
            print "<td class='menuoption'><a href='shell.php'>Console</a>*</td>";
?>
       
         
      </tr>
   </table>
