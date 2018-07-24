<?php

	require_once('vendor/autoload.php');
	require_once('app/clases/google_auth.php');
	require_once('app/init.php');

	$googleClient = new Google_Client();
	$auth = new GoogleAuth($googleClient);

	if($auth->checkRedirectCode()){
		//die($_GET['code']);
		header('Location: index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php if (!$auth->isLoggedIn()){ ?>
		<a href="<?php echo $auth->getAuthUrl(); ?>">Iniciar con Google</a>
	<?php }else{ ?>
		Bienvenido...  <a href="logout.php">Cerrar sesion</a>
	<?php } ?>
</body>
</html>