<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $mail->subject; ?></title>
	<style>
		body {
			background: #fff;
			color: #000;
			margin: 0;
			padding: 0;
			font-family: helvetica neue, helvetica, arial, verdana, sans-serif;
			font-size: 20px;
			font-weight: 100;
		}

		a, a:hover, a:active {
			color: #2c5999;
		}

		div.wrapper {
			width: 500px;
			padding: 10px;
			margin: 100px auto;
			border: 1px solid #2c5999;
		}
		div.header {
			width: 480px;
			margin: 0;
			padding: 10px;
			background-color: #2c5999;
		}

		div.header h1 {
			font-size: 28px;
			color: #fff;
			margin: 0;
		}
		
		div.body {
			width: 480px;
			padding: 10px;
		}

		div.body p {
			line-height: 28px;
		}

		div.footer {
			width: 480px;
			padding: 10px;
		}

		div.footer p {
			font-size: 14px;
		}
	</style>
</head>
<body>

	<div class="wrapper">
		<div class="header"><h1>Morning Pages</h1></div>
		<div class="body">
			<p><?php echo $mail->content ?></p>
		</div>
		<div class="footer">
			<p>Can't read this e-mail?<br /><a href="<?php echo $mail->url(); ?>" title="Read this e-mail in your browser instead" target="_blank">See it in your browser</a>.</p>
		</div>
	</div>

</body>
</html>