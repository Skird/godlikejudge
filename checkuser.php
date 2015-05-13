<?
	function checkuser($user, $pass)
	{
		$str_us = file_get_contents("data/users") or die("Unable to get userlist");
		$str_arr = explode("\n", $str_us);    

		for ($i = 0; $i < count($str_arr); $i++)
		{
			$info = explode("-", $str_arr[$i]);
			if (trim($info[0]) == trim($user) && strlen(trim($str_arr[$i])) != 0 && trim($info[2]) == trim($pass)) return 1;
		}
		return 0;
	}

	function is_user_exists($user)
	{
		$str_us = file_get_contents("data/users") or die("Unable to get userlist");
		$str_arr = explode("\n", $str_us);    

		for ($i = 0; $i < count($str_arr); $i++)
		{
			$info = explode("-", $str_arr[$i]);
			if (trim($info[0]) == trim($user) && strlen(trim($str_arr[$i])) != 0) return 1;
		}
		return 0;
	}

	function getname($user)
	{
		$str_us = file_get_contents("data/users") or die("Unable to get userlist");
		$str_arr = explode("\n", $str_us);    

		for ($i = 0; $i < count($str_arr); $i++)
		{
			$info = explode("-", $str_arr[$i]);
			if (trim($info[0]) == trim($user) && strlen(trim($str_arr[$i])) != 0) return $info[1];
		}
		return "";
	}

	function logged_in()
	{
		if (empty($_SESSION['user']) || empty($_SESSION['pass'])) return 0;
		return 1;
	}

	function check_login()
	{
		if (!logged_in())
		{
?>
			<p><a href='login.php'>Log in</a></p>
<?
			die("");
		}
		if (!checkuser($_SESSION['user'], $_SESSION['pass']))
		{
			session_destroy();
?>
			<p><a href='login.php'>Log in</a></p>
<?
			die("");
		}
	}
	
	function check_admin($user, $pass)
	{
		$str_us = file_get_contents("data/admins") or die("Unable to get userlist");
		$str_arr = explode("\n", $str_us);    

		for ($i = 0; $i < count($str_arr); $i++)
		{
			$info = explode("-", $str_arr[$i]);
			if (trim($info[0]) == trim($user) && strlen(trim($str_arr[$i])) != 0 && trim($info[2]) == trim($pass)) return 1;
		}
		return 0;
	}

	function check_admin2($user)
	{
		$str_us = file_get_contents("data/admins") or die("Unable to get userlist");
		$str_arr = explode("\n", $str_us);    
		for ($i = 0; $i < count($str_arr); $i++)
		{
			$info = explode("-", $str_arr[$i]);
			if (trim($info[0]) == trim($user) && strlen(trim($str_arr[$i])) != 0) return 1;
		}
		return 0;
	}
?>
