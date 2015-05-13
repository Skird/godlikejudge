<?
   include("security.php");
   if (!check_admin($_SESSION['user'], $_SESSION['pass'])) die("You have not access for this page");

   $contest = empty($_GET['contest']) ? $_SESSION['contest'] : $_GET['contest'];
   loadContest($contest);
      
   if (!empty($_POST['create']))
   {
      $contest = $_POST['contestname'];
      write_string("data/contests/$contest", "");
      loadContest($contest);
   }
         
   if (!empty($_POST['save']))
   {
      $filename = "data/contests/$contest";
  
      $oldprob = split(",", $_POST['probstr']);
      $nprob = array();
      $cnt = 0;
      foreach ($oldprob as $key => $value) if (trim($value) != "") $nprob[$cnt++] = trim($value);
      
      $probstr = implode(", ", $nprob);
      $scores = $_POST['show_scores'] == "on" ? 1 : 0;
      $results = $_POST['show_results'] == "on" ? 1 : 0;
      $monitor = $_POST['show_monitor'] == "on" ? 1 : 0;
      $token = $_POST['token_regeneration'];
      $start = $_POST['start_time'];
      $len = $_POST['duration'];
      $upsolving = $_POST['upsolving'];
      $access = trim($_POST['access']);
      file_put_contents($filename, "");
      append_string($filename, "problems: $probstr\n");
      append_string($filename, "show_scores: $scores\n");
      append_string($filename, "show_results: $results\n");
      append_string($filename, "show_monitor: $monitor\n");
      append_string($filename, "token_regeneration: $token\n");
      append_string($filename, "start_time: $start\n");
      append_string($filename, "duration: $len\n");
      append_string($filename, "access: $access\n");
      append_string($filename, "upsolving: $upsolving\n");
       
      loadContest($contest);
   }
   
   $contest_dir = opendir("data/contests");
   $available = array();
   while (true == ($dir = readdir($contest_dir)))
      if ($dir != "." && $dir != "..")
         $available[count($available)] = $dir;
   sort($available);
?>
<html>
<body onload="loadAll()">
<?
   include('header.php');   
?>
      <div class='panel'>
         <p>Contest to edit:</p>
         <ul>
<?
            foreach ($available as $key => $dir)
            {
            if (trim($dir) == trim($contest)) print "<b>";
            print "<li><a href='manage.php?contest=$dir'>$dir</a></li>";
            if (trim($dir) == trim($contest)) print "</b>";
            }
?>
         </ul>
         <div>Warning: choosing contest be sure you have saved all changes in current contest</div>
      </div>
      <div class='panel'>
<?
         $problemsline = implode(", ", $pname);
?>
         <div>Edit contest</div>
         <div>Name: <? print $contest; ?></div>
         <form action="manage.php?contest=<? print $contest; ?>" method="post">
            <div class = 'panel'>
               <label>List of problems:</label>
               <input type = "text" size = 80  name = 'probstr' id='probstr' value = ''><br>
                <script language="javascript">
                  var select = [];
                  function trim(str)
                  {
                     while(str.length > 0 && str.charAt(0) == ' ') str = str.substr(1, str.length-1);
                     while(str.length > 0 && str.charAt(str.length-1) == ' ') str = str.substr(0, str.length-1); 
                     return str;
                  }
                  function remOne(str)
                  {
                  //   alert("rem(" + str + ")");
                     for (var i = 0; i < select.length; i++)
                        if (select[i] == str) 
                        {
                           if (select.length == 1) select = [];
                           else select.splice(i,1);
                           break;                             
                        }                    

                  }
                  
                  function addOne(str)
                  {
                     var ok = 1;
                   //  alert("add(" + str + ")");
                     str = trim(str);
                     for (var i = 0; i < select.length; i++) select[i] = trim(select[i]);
                     for (var i = 0; i < select.length && ok != 0; i++)
                     {
                      //  alert(select[i]);
                        if (select[i] == str) 
                        {                            
                           remOne(str); 
                           ok = 0;
                        }
                     }
                     //alert(ok);
                     if (ok) select.push(str);
                     var ns = [];
                     for (var i = 0; i < select.length; i++)
                     {
                        if (select[i] != "") ns.push(select[i]);
                     }
                     select = ns;
                   //  alert("sz=" + select.length);      
                     document.getElementById("probstr").value = select.join(", ");
                     if (ok) document.getElementById("problem." + str).innerHTML = "<font color='#0000FF'>" + str + "</font>";
                     else document.getElementById("problem." + str).innerHTML = str;              
                  }
                  function loadAll()
                  {
                     <?
                        foreach($pname as $key => $value) echo "addOne('$key');"
                     ?>
                  }
               </script>
               
<?
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
      for ($i = 0; $i < $cnt0; $i++) echo "<a onclick='addOne(\"{$drs[$i]}\")' id = 'problem.{$drs[$i]}'>{$drs[$i]}</a> ";
?>
            <br>
            
            </div>
            <div>
               <input type="checkbox" name="show_scores" <? if ($show_scores) print "checked"; ?> />
               <label>Show scores</label>
            </div>
            <div>
               <input type="checkbox" name="show_results" <? if ($show_results) print "checked"; ?> /> 
               <label>Show results</label>
            </div>
            <div>
               <input type="checkbox" name="show_monitor" <? if ($show_monitor) print "checked"; ?> />
               <label>Show monitor</label>
            </div>
            <div>
               <label>Token regeneration (in seconds):</label>
               <input type="text" name="token_regeneration" value="<? print $token_regeneration; ?>" />
            </div>
            <div>
               <script language="javascript">
                  function setStartTime()
                  {
                     var cur = <? print time(); ?>;
                     var offset = parseInt(document.getElementById("offset").value);
                     document.getElementById("start_time").value = cur + offset;
                  }
               </script>
               <label>Start time (Unix):</label>
               <input type="text" name="start_time" id="start_time" value="<? print $contest_start; ?>" />
               <input type="button" value="Set current timestamp + " onclick="setStartTime()" />
               <input type="text" id="offset" value="0" style="width:80px" />s
            </div>
            <div>
               <label>Duration (seconds): </label>
               <input type="text" name="duration" value="<? print $contest_duration; ?>" />
            </div>
             <div>
               <label>Upsolving: </label>
               <input type="checkbox" name="upsolving" <? if ($upsolving=="on") print " checked "; ?> />
            </div>
           
            <div>
              <label>Access: </label>
              <input type="text" name="access" value="<? print $access; ?>" size=60 />
            </div>
            <div>
               <input type="submit" name="save" value="Save changes" />
            </div>
         </form>
      </div>
      <div class='panel'>
         <div>Or create new contest</div>
         <form action='manage.php' method='post'>
            <input type='text' name='contestname' />
            <input type='submit' name='create' value='Create empty contest' />
         </form>
      </div>
   </body>
</html>
