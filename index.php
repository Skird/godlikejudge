<?
   include("security.php");
   $over = $contest_start + $contest_duration < time();
   $before = $contest_start > time();
   $admin = check_admin($_SESSION['user'], $_SESSION['pass']);
?>
<html>
   <? 
     include("header.php"); 
   //  echo "over=$over, before=$before<br>";
     if (($over || $before) && !$admin) die("submit is impossible"); 
   ?>
      <form method='post' action='send.php'>
         <p><b>Submit</b></p>
         <div>
            <table>
               <tr>
                  <td>Problem</td>
                  <td>
                     <select name='problem' size='1'>
                        <?
                           foreach ($pname as $key => $value)
                              print "<option value = '$key'>$key - $value</option>";
                        ?>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td>Language</td>
                  <td>
                     <select name='lang' size='1'>
                        <option value='cpp' checked>GNU C++</option>
                        <option value='pascal'>Free Pascal</option>
                        <option value='java'>Java 7</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td>Source</td>
                  <td>
                     <textarea rows = '10' cols = '50' name = 'code'></textarea>
                  </td>
               </tr>
            </table>
         </div>
         <input type = 'submit' name = 'submit' value = 'Submit' class=mbutt />
      </form>
   </body>
</html>
