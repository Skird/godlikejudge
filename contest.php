<?  
	function parseSetting($line)
	{
		global $access, $upsolving, $pname, $show_scores, $show_results, $show_monitor, 
			   $token_regeneration, $contest_start, $contest_duration, $show_statements, $show_codes;
		$setting = explode(":", $line);
		if (trim($setting[0]) == "problems")
		{
			$plist = explode(",", $setting[1]);
			foreach ($plist as $problem)
				$pname[trim($problem)] = trim($problem);
		}
		if (trim($setting[0]) == "show_scores") $show_scores = (int) ($setting[1]);
		if (trim($setting[0]) == "show_results") $show_results = (int) ($setting[1]);
		if (trim($setting[0]) == "show_monitor") $show_monitor = (int) ($setting[1]);
		if (trim($setting[0]) == "token_regeneration") $token_regeneration = (int) ($setting[1]);
		if (trim($setting[0]) == "start_time") $contest_start = (int) ($setting[1]);
		if (trim($setting[0]) == "duration") $contest_duration = (int) ($setting[1]);
		if (trim($setting[0]) == "show_statements") $contest_duration = (int) ($setting[1]);
		if (trim($setting[0]) == "access") $access = trim($setting[1]);
		if (trim($setting[0]) == "upsolving") $upsolving = trim($setting[1]) == "on";
	}

	function importSettings($contest)
	{
		$data = file_get_contents("data/contests/$contest");
		$rows = explode("\n", $data);
		foreach ($rows as $line)
			parseSetting($line);
	}

	function valueOfSetting($contest, $name)
	{
		$data = file_get_contents("data/contests/$contest");
		$rows = explode("\n", $data);
		foreach ($rows as $line)
		{
			$setting = explode(":", $line);
			if (trim($setting[0]) == trim($name)) return trim($setting[1]); 
		}     
		return "";
	}

	function check_access($contest, $user)
	{
		$accv = valueOfSetting($contest, "access");
		if ($accv == "public") return 1;
		$users = split(",", $accv);
		foreach ($users as $value) 
			if (trim($value) == trim($user)) return 1; 
		return 0;
	}
   
	function loadContest($contest)
	{
		global $pname, $show_scores, $upsolving, $show_results, $show_monitor, $token_regeneration, $contest_start, $contest_duration, $show_statements, $show_codes, $access;
		$pname = array();
		importSettings($contest);   
	}
?>                          
