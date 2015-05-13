<?
   include("security.php");
?>
<html>
<?
   include("header.php");
      if (empty($_POST['subject']) || empty($_POST['text']))
      {
?>
         <form method = 'post' action = 'clar.php'>
            <p>Subject: <input type='text' name='subject' size='30' maxlength='50' /></p>
            <p>Text:</p>
            <div><textarea cols='50' rows='20' name='text'></textarea></div>
            <p><input type='submit' value='Request clarification' class='mbutt'></p>
         </form>
<?
      }
      else
      {
         writeLog("$user sends question");
         $num = (int) trim(file_get_contents("data/clarcnt"));
         write_string("data/clarcnt", $num + 1);
         write_string("data/clars/$num.subj", $_POST['subject']);
         write_string("data/clars/$num.text", $_POST['text']);
         write_string("data/clars/$num.user", $user);
         print "<div><p>Request was successfully sent.</p></div>";
      }
?> 
   </body>
</html>
