<?php

require_once('header.php');

if ($_SERVER['REQUEST_METHOD'] === "POST") {
	$newfile = '';
	if ($fp = fopen('settings.conf', 'w')) {
		foreach ($_POST AS $key=>$value) {
			$newfile .= $key."=".$value."\n";
		}
		fputs($fp, $newfile);
		fclose($fp);
	}
}

$settings = array();

if (!file_exists('settings.conf')) {
	if ($fp = fopen('settings.conf', 'w')) {
		fputs($fp, '');
		fclose($fp);
	}
}

$file = file('settings.conf');
foreach($file AS $line) {
	$data = explode('=', $line);
	$settings[$data[0]] = $data[1];
}

?>
			<form action="index.php" method="POST">
				<table>
					<tr>
						<td>Username:</td>
						<td><input type="text" value="<?php echo isset($settings['username']) ? $settings['username'] : ''; ?>" name="username" /></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="text" value="<?php echo isset($settings['password']) ? $settings['password'] : ''; ?>" name="password" /></td>
					</tr>
					<tr>
						<td>Server Address:</td>
						<td><input type="text" value="<?php echo isset($settings['address']) ? $settings['address'] : ''; ?>" name="address" /></td>
					</tr>
					<tr>
						<td>REALMD:</td>
						<td><input type="text" value="<?php echo isset($settings['realmd']) ? $settings['realmd'] : ''; ?>" name="realmd" /></td>
					</tr>
					<tr>
						<td>MANGOS2:</td>
						<td><input type="text" value="<?php echo isset($settings['mangos2']) ? $settings['mangos2'] : ''; ?>" name="mangos2" /></td>
					</tr>
					<tr>
						<td colspan="2"><input type="Submit" value="Save Settings" /></td>
					</tr>
				</table>
			</form>
<?php require_once("footer.php"); ?>