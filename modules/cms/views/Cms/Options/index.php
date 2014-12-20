<div class="page-header">
	<h1><?php echo __('Settings'); ?></h1>
</div>
<div id="view-options" class="editable">
	
	<form id="options-form" id="options-form" name="options-form" action="options" method="post">
		<div class="row">
			<div class="col-xs-9">
				<ul class="nav nav-tabs" data-bind="tabs">
<?php
					$groups = ORM::factory('Optiongroup')->find_all();
					if((bool)$groups->count())
					{
						 $i = 1;
						foreach($groups as $group)
						{
							echo '<li class="'.($i==1?'active':'').'">';
							echo HTML::anchor('#tab-'.$group->slug, $group->display);
							echo '</li>';
							$i++;
						}
					}
?>
				</ul>
<?php
				if((bool)$groups->count())
				{
					echo '<div class="tab-content">';
					$i = 1;
					foreach($groups as $group)
					{
						echo '<div class="tab-pane'.($i==1?' active':'').'" id="tab-'.$group->slug.'">';
						foreach($group->options->where('editable','=',1)->find_all() as $option)
						{
							echo '<div class="form-group option" data-optionid="'.$option->id.'">';
							echo '<label for="option-' . $option -> id . '">' . $option -> title . '</label>';
							switch($option->type)
							{
								default:
								case 'text':
									echo '<input class="form-control" type="text" name="option[' . $option -> id . ']" id="option-' . $option -> id . '" value="' . htmlentities($option -> value) . '" />';
									break;
								case 'textarea':
									echo '<textarea class="form-control" name="option[' . $option -> id . ']" id="option-' . $option -> id . '">' . $option -> value . '</textarea>';
									break;
								case 'richtext':
									echo '<textarea class="form-control tinymce" data-bind="tinymce:true" name="option[' . $option -> id . ']" id="option-' . $option -> id . '">' . $option -> value . '</textarea>';
									break;
								case 'file':
									$files = false;
									$file = ORM::factory('File',$option->value);
									if($file->loaded())
									{
										$files = $file;
									}
									//echo files::filebrowser(1, $files, 'filebrowsersettingsbtn');
									break;
							}
							echo '<p class="help-block"><em>' . $option -> description . '</em></p>';
							echo '</div>';
							$i++;
						}
						echo '</div>';
					}
					echo '</div>';
				}
?>
			</div>
			<div class="col-xs-3">
				<div class="widget">
					<div class="widget-header">
						<h3 class="widget-title"><?php echo __('Actions') ?></h3>
					</div>
					<div class="widget-body">
						<button class="btn btn-primary editable-save-btn">
							<span class="glyphicon glyphicon-floppy-save"></span>
							<span class="text"><?php echo __('Save changes'); ?></span>
						</button>
						<div class="editable-warning hidden">
							Ikke gemt!
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>