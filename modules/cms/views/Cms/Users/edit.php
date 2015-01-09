<div class="page-header">
	<h1><?php echo __('Edit user'); ?></h1>
</div>

<form action="users/edit/<?php echo $user->id; ?>" method="post">
	<div class="row">
		<div class="col-xs-12 col-md-9">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#infotab" data-toggle="tab">Info</a>
				</li>
				<li>
					<a href="#pagestab" data-toggle="tab">Pages (<?php echo $user->pages->where('type','=','page')->count_all(); ?>)</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="infotab">
					<div class="form-group">
						<label for="user-edit-email"><?php echo __('E-mail:'); ?></label>
						<input type="email" name="email" id="user-edit-email" data-bind="value:user.email" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-name"><?php echo __('Name:'); ?></label>
						<input type="text" name="name" id="user-edit-name" data-bind="value:user.name" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-avatar"><?php echo __('Avatar:'); ?></label>
						<img src="" data-bind="src:user.gravatar.medium()" />
					</div>
					<div class="form-group">
						<label for="user-edit-bio"><?php echo __('Bio:'); ?></label>
						<textarea name="bio" id="user-edit-bio" data-bind="value:user.bio" class="form-control"></textarea>
					</div>
					<div class="form-group">
						<label for="user-edit-website"><?php echo __('Website:'); ?></label>
						<input type="text" name="website" id="user-edit-website" data-bind="value:user.website" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-longest_streak"><?php echo __('Longest streak:'); ?></label>
						<input type="text" name="longest_streak" id="user-edit-longest_streak" data-bind="value:user.longest_streak" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-current_streak"><?php echo __('Current streak:'); ?></label>
						<input type="text" name="current_streak" id="user-edit-current_streak" data-bind="value:user.current_streak" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-most_words"><?php echo __('Most words:'); ?></label>
						<input type="text" name="most_words" id="user-edit-most_words" data-bind="value:user.most_words" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-all_time_words"><?php echo __('All time words:'); ?></label>
						<input type="text" name="all_time_words" id="user-edit-all_time_words" data-bind="value:user.all_time_words" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-points"><?php echo __('Points:'); ?></label>
						<input type="text" name="points" id="user-edit-points" data-bind="value:user.points" class="form-control" />
					</div>
					<div class="form-group">
						<em><?php echo __('Only fill out the below fields if you want to change the users password'); ?></em>
					</div>
					<div class="form-group">
						<label for="user-edit-password"><?php echo __('Password:'); ?></label>
						<input name="password" type="password" id="user-edit-password" class="form-control" />
					</div>
					<div class="form-group">
						<label for="user-edit-password"><?php echo __('Password confirm:'); ?></label>
						<input name="password_confirm" type="password" id="user-edit-password-confirm" class="form-control" />
					</div>
				
				</div>
				<div class="tab-pane" id="pagestab">
					
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Date</th>
								<th>Wordcount</th>
								<th>Duration</th>
								<th>Points</th>
							</tr>
						</thead>
						<tbody>
	<?php
							$pages = $user->pages
								->where('type','=','page')
								->order_by('created', 'DESC')
								->find_all();
							if((bool)$pages->count())
							{
								foreach($pages as $page)
								{
									echo '<tr>';
									echo '<td>'.$page->day.'</td>';
									echo '<td>'.$page->wordcount.'</td>';
									echo '<td>'.$page->duration.'</td>';
									echo '<td>'.$page->points.'</td>';
									echo '</tr>';
								}
							}
	?>
						</tbody>
					</table>
					
				</div>	
			</div>
			
		</div>
		<div class="col-xs-12 col-md-3">
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Info'); ?></h3>
				</div>
				<div class="widget-body">
					
					<div class="row">
						<div class="col-xs-4 text-right">
							<strong><?php echo __('Created:'); ?></strong>
						</div>
						<div class="col-xs-8" data-bind="text:user.created.formatted"></div>
					</div>
					
					<div class="row">
						<div class="col-xs-4 text-right">
							<strong><?php echo __('Login count:'); ?></strong>
						</div>
						<div class="col-xs-8" data-bind="text:user.logins"></div>
					</div>
					
					<div class="row">
						<div class="col-xs-4 text-right">
							<strong><?php echo __('Last login:'); ?></strong>
						</div>
						<div class="col-xs-8" data-bind="text:user.last_login.formatted"></div>
					</div>
					
				</div>
			</div>
			
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Roles'); ?></h3>
				</div>
				<div class="widget-body">
					
					<ul data-bind="foreach:user.roles" class="tags">
						<li class="tag">
							<input type="hidden" name="roles[]" data-bind="value:name" />
							<a href="#" data-bind="text:name,attr:{href:'#/users?role='+realname},tooltip:{title:description}" data-toggle="tooltip"></a>
							<a href="#" data-bind="click:$root.user.removeRole" class="remove">X</a>
						</li>
					</ul>
					<hr />
					<div class="form-group form-inline">
						<select class="form-control" id="edit-user-add-role-select" data-bind="options:availableRoles,optionsCaption:'Vælg...',optionsText:'name',optionsValue:'id'">
							
		<?php
							/*$roles = ORM::factory('Role')->find_all();
							if((bool)$roles->count())
							{
								foreach($roles as $role)
								{
									if(!$user->has('roles', $role->id))
									{
										echo '<option value="'.$role->id.'">'.$role->name.'</option>';
									}
								}
							}*/
		?>
						</select>
						<button class="btn btn-primary" data-bind="click:addRole">
							<span class="glyphicon glyphicon-plus"></span>
							<?php echo __('Add'); ?>
						</button>
					</div>
				</div>
			</div>
			
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Actions'); ?></h3>
				</div>
				<div class="widget-body">
					<button class="btn btn-primary">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<?php echo __('Save'); ?>
					</button>
					<hr />
					<div class="text-right">
						<button class="btn btn-danger btn-xs" data-bind="click:showDeleteUserModal">
							<span class="glyphicon glyphicon-trash"></span>
							<?php echo __('Delete'); ?>
						</button>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</form>

<div class="modal" id="edit-user-delete">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo __('Delete user'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="edit-user-delete-content-action"><?php echo __('What do you want to do with content belonging to this user?'); ?></label>
					<select data-bind="value:deleteaction" class="form-control" id="edit-user-delete-content-action">
						<option value="transfer"><?php echo __('Transfer to other user'); ?></option>
						<option value="delete"><?php echo __('Delete'); ?></option>
					</select>
				</div>
				<div class="form-group" data-bind="visible:deleteaction()=='transfer'">
					<label for="edit-user-delete-content-action-transfer-user"><?php echo __('Select user:'); ?></label>
					<select id="edit-user-delete-content-action-transfer-user" class="form-control">
<?php
						$users = ORM::factory('User')
							->order_by('username', 'ASC')
							->find_all();
						if((bool)$users->count())
						{
							foreach($users as $user)
							{
								echo '<option value="'.$user->id.'">'.$user->username.'</option>';
							}
						}
?>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Undo'); ?></button>
				<button type="button" class="btn btn-danger" data-bind="click:deleteUser"><?php echo __('Delete user') ?></button>
			</div>
		</div>
	</div>
</div>

