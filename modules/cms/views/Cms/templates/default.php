<!DOCTYPE html>
<html lang="en">
<head>
	<title>CMS | <?php echo cms::option('sitename'); ?></title>
	<?php echo HTML::style('cms/media/css/bootstrap.min.css'); ?>
	<?php echo HTML::style('cms/media/libs/fontawesome/css/font-awesome.min.css'); ?>
	<?php echo HTML::style('cms/media/libs/jquery-file-upload/css/jquery.fileupload-ui.css'); ?>
	<?php echo HTML::style('cms/media/css/style.css'); ?>
</head>
<body>

<div class="container" id="wrap">
	<header class="row" id="header">
		<div class="col-xs-2 logo">
			<h1>
				<a href="#" title="CMS"><?php echo cms::option('sitename', 'CMS'); ?></a>
			</h1>
		</div>
		<div class="col-xs-10 actions">
			<div class="row">
				<div class="col-xs-2">
					<div data-bind="loader:loading()"></div>
				</div>
				<div class="col-xs-10 text-right">
					<div class="btn btn-default">
						<?php echo __('Hello :name', array(':name' => user::get()->username)); ?>!
					</div>
					<a href="#" id="header-filesbtn" class="btn btn-default" title="<?php echo __('Browse files'); ?>">
						<span class="glyphicon glyphicon-floppy-disk"></span>
					</a>
					<a href="#/messages" class="btn btn-default" data-bind="css:{'btn-default':unread_messages()==0,'btn-warning':unread_messages()>0}">
						<span class="glyphicon glyphicon-envelope"></span>
						<span data-bind="text:unread_messages(),visible:unread_messages()>0"></span>
					</a>
					<a href="#" class="btn btn-info" id="site-info">
						<span class="glyphicon glyphicon-user"></span>
						<span data-bind="text:current_visitors()">0</span>
					</a>
					<?php if(user::logged('developer')): ?>
						<a href="#/super" class="btn btn-primary" title="Superadmin">
							<span class="glyphicon glyphicon-lock"></span>
						</a>
					<?php endif; ?>
					<a href="<?php echo URL::site(localization::get('users.urls.logout')); ?>" data-bind="click:logout" class="btn btn-danger" title="<?php echo __('Log out') ?>">
						<span class="glyphicon glyphicon-off"></span>
					</a>
				</div>
			</div>
		</div>
	</header>
	<div class="row" id="mainwrap">
		<div class="col-xs-12 col-md-2" id="sidebar">
			<ul class="nav nav-pills nav-stacked">
				<li id="navitem-welcome">
					<a href="#">
						<span class="glyphicon glyphicon-home"></span>
						<?php echo __('Controlpanel') ?>
					</a>
				</li>
			</ul>
			<h3><small><?php echo __('Content'); ?></small></h3>
			<ul class="nav nav-pills nav-stacked">
<?php
				$contenttypes = ORM::factory('Contenttype')
					->find_all();
				if((bool)$contenttypes->count())
				{
					foreach($contenttypes as $contenttype)
					{
						if($contenttype->type != 'util')
						{
							echo '<li id="navitem-contenttype-'.$contenttype->id.'">';
							echo '<a href="#/content/index/'.$contenttype->id.'">';
							if($contenttype->icon == '')
							{
								echo '<span class="glyphicon glyphicon-file"></span>&nbsp;';
							}
							else
							{
								echo '<span class="'.$contenttype->icon.'"></span>&nbsp;';
							}
							echo $contenttype->display;
							echo '</a>';
							echo '</li>';
						}
					}
				}
?>
			</ul>
			<h3><small><?php echo __('Settings'); ?></small></h3>
			<ul class="nav nav-pills nav-stacked">
<?php
				$util = ORM::factory('Contenttype')
					->where('type','=','util')
					->find();
				if($util->loaded())
				{
					echo '<li id="navitem-contenttype-'.$util->id.'">';
					echo '<a href="#/content/index/'.$util->id.'">';
					if($util->icon == '')
					{
						echo '<span class="glyphicon glyphicon-file"></span>&nbsp;';
					}
					else
					{
						echo '<span class="'.$util->icon.'"></span>&nbsp;';
					}
					echo $util->display;
					echo '</a>';
					echo '</li>';
				}
?>
				<li id="navitem-navigation">
					<a href="#/navigation">
						<span class="fa fa-sitemap"></span>
						<?php echo __('Navigation'); ?>
					</a>
				</li>
				<li id="navitem-users">
					<a href="#/users">
						<span class="fa fa-user"></span>
						<?php echo __('Users'); ?>
					</a>
				</li>
				<li id="navitem-options">
					<a href="#/options">
						<span class="glyphicon glyphicon-cog"></span>
						<?php echo __('Settings'); ?>
					</a>
				</li>
			</ul>
		</div>
		
		<div class="col-xs-12 col-md-10 col-md-offset-2" id="content">
			<div id="content-holder"></div>
			<footer class="row" id="footer"></footer>
		</div>
		
	</div>
</div>

<?php echo View::factory('Cms/Modals/filebrowser'); ?>
<?php echo View::factory('Cms/Modals/fileeditor'); ?>
<script>
	var sitelocale = '<?php echo site::jslocale(); ?>';
</script>
<?php echo HTML::script('cms/media/js/vendor/require.js', array('data-main' => cms::url('media/js/project.js'))); ?>
</body>
</html>
