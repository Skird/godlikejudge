<?
   function write_string($filename, $str)
   {
      file_put_contents($filename, $str);
   }

   function append_string($filename, $str)
   {
      $handle = fopen($filename, "a");
      fputs($handle, $str);
      fclose($handle);
   }
   
   function formatTime($time)
   {
      $h = (int) ($time / 3600);
      $m = (int) ($time / 60) % 60;
      $s = ($time % 60);
      return sprintf("%d:%02d:%02d", $h, $m, $s);
   }

   function writeLog($string)
   {
      append_string("data/log.txt", date("[F d Y] [H:i:s] ").$string."\n");
   }
?>
