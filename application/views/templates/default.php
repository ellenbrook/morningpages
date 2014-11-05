<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo seo::instance()->title(); ?> | Morning Pages</title>
	<?php /* <script src="//use.typekit.net/rod6iku.js"></script>
	<script>try{Typekit.load();}catch(e){}</script> */ ?>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="Morning Pages" name="name">
	<meta content="<?php echo seo::instance()->description(); ?>" name="description">
	<link href="<?php echo URL::site('media/img/favicon.ico'); ?>" rel="shortcut icon" />
	<link rel="apple-touch-icon" href="<?php echo URL::site('media/img/favicon.png'); ?>" />
	<link rel="stylesheet" type="text/css" id="mainstyles" href="<?php echo URL::site('media/css/style.css'); ?>" />
</head>
<?php
	$theme = '';
	if(user::logged() && user::get()->option->theme_id)
	{
		$theme = ORM::factory('Theme',user::get()->option->theme_id)->name;
	}
?>
<body class="<?php echo $theme; ?>">

<?php
	echo View::factory('templates/header');
?>

<section class="main">
	<div class="container">
		<?php echo $view; ?>
	</div>
</section>

<?php echo View::factory('templates/footer'); ?>

<script src="<?php echo URL::site('media/js/require.js'); ?>" type="text/javascript"></script>
<script src="<?php echo URL::site('media/js/config.js'); ?>" type="text/javascript"></script>
<script>
<?php
	$filename = 'media/js/viewModels/'.$controller.'/'.$action.'.js';
	$include_viewmodel = false;
	if(file_exists($filename))
	{
		$include_viewmodel = true;
	}
?>
	require(['project'], function(project){
		project.init(<?php echo (user::logged()?'true':'false').', '.site::notes(); ?>).then(function(){
			<?php if($include_viewmodel): ?>
				require(['viewModels/<?php echo $controller.'/'.$action; ?>']);
			<?php endif; ?>
		});
	});
</script>

</body>
</html>
