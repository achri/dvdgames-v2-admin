<?php
// BASE DIRECT OPENSSL OR NATIVE SERVER PROTOCOL @AHRIE
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
	$wwwp = 'https://';
} else {
	$wwwp = 'http://';
}
$name = $_SERVER['SERVER_NAME'];
$host = $wwwp.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];
?>
<html>
<head>
<title>Redirect to <?php echo $name?></title>
<META http-equiv="refresh" content="1;URL=<?php echo $host?>">
</head>
<body>
</body>
</html> 