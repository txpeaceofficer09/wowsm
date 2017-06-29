<form action="newaccount.php" method="POST">
Username: <input type="text" name="username" /><br />
Password: <input type="password" name="password" /><br />
E-Mail: <input type="email" name="email" /><br />
GM Level: <select name="gmlevel">
		<option value="0">Player</option>
		<option value="1">Moderator</option>
		<option value="2">Game Master</option>
		<option value="3">Administrator</option>
		</select><br />
Expansion: <select name="expansion">
		<option value="0">Vanilla WoW</option>
		<option value="1">The Burning Crusades</option>
		<option value="2" selected>The Wrath of the Lich King</option>
		<option value="3">Cataclysm</option>
		<option value="4">Mists of Pandaria</option>
		</select><br />
		<input type="submit" value="Create Account" />
</form>

<?php

$mysqli = new mysqli('10.238.168.134', 'root', 'trinity');

$mysqli->query('INSERT INTO `realmd`.`account` (`sha_pass_hash`, `username`, `email`, `gmlevel`, `expansion`) VALUES ("'.strtoupper(sha1(strtoupper($_GET['username'].':'.$_GET['password']))).'", "'.$_GET['username'].'", "'.$_GET['email'].'", "'.$_GET['gmlevel'].'", "'.$_GET['expansion'].'");');

$mysqli->close();

?>