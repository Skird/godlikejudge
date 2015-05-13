<? 
   include("security.php");
   if (!check_admin($user, $_SESSION['pass']))
      die("This page is not available for you");   
?>

<html>
<? 
      include("header.php");
    //  echo "some1<br>";
      if (isset($_POST['answer']) && $_POST['answer'] != "")
      {
        // echo "some2<br>";
         $num = $_POST['id'];
         write_string("data/clars/$num.ans", $_POST['answer']);
         if ((bool) $_POST['forall']) write_string("data/clars/$num.user", "forall");
?>
         <div>Answer successfully sent</div>
         <div><a href='answer.php'>Go back</a></div>
<?
      }
?>
   </body>
</html>
