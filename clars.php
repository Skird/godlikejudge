<?
   include("security.php");
?>
<html>
<?
   include("header.php");
  
      $num = (int) file_get_contents("data/clarcnt");
      $isadmin = check_admin($user, $_SESSION['pass']);
      for ($i = $num - 1; $i >= 0; $i--)
      {
         $curuser = trim(file_get_contents("data/clars/$i.user"));
         if ($curuser == "") continue;
         if (trim($curuser) == trim($user) || ($isadmin) || $curuser == "forall")
         {
            $subject = trim(file_get_contents("data/clars/$i.subj"));
            $text = trim(file_get_contents("data/clars/$i.text"));

?>
            <div>
               <p>Subject: <? print $subject; ?></p>
<?
               if ($isadmin) 
               {
                  print "<div>User: <b>";
                  if ($curuser == "forall") print "Broadcasted";
                  else print getname($curuser);
                  print "</b></div>";
               }
?>
               <p>Clarification:</p>
               <textarea cols='50' rows='4' readonly><? print $text; ?></textarea>
<?
               if (file_exists("data/clars/$i.ans"))
               {
                  $ans = file_get_contents("data/clars/$i.ans");
?>
                  <textarea cols='50' rows='4' readonly><? print $ans; ?></textarea>
<?
               }
               else if ($isadmin && $curuser != "forall" && !file_exists("data/clars/$i.ans"))
               {
?>
                  <form method='post' action='answer.php'>
                     <p><textarea cols='50' rows='4' name='answer'></textarea></p>
                     <p><input type='checkbox' name='forall' />Broadcast</p>
                     <p><input type='submit' value='Answer' class = 'mbutt'/></p>
                     <input type='hidden' name='id' value='<? print $i; ?>' />
                  </form>                                    
<?
               }
?>
            </div>
<?
         }
      }
?>
   </body>
</html>
