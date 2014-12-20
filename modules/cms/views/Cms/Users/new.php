<div class="page-header">
	<h1><?php echo __('New user'); ?></h1>
</div>

<form action="users/create" method="post">
	<div class="row">
		
		<div class="col-xs-12 col-md-9">
			<div class="form-group">
				<label for="user-edit-email"><?php echo __('E-mail:'); ?></label>
				<input type="email" name="email" id="new-user-email" class="form-control" />
			</div>
			<div class="form-group">
				<label for="user-edit-name"><?php echo __('Name:'); ?></label>
				<input type="text" name="name" id="new-user-name" class="form-control" />
			</div>
			<div class="form-group">
				<label for="user-edit-bio"><?php echo __('Bio:'); ?></label>
				<textarea name="bio" id="new-user-bio" class="form-control"></textarea>
			</div>	
			<div class="form-group">
				<label for="user-edit-password"><?php echo __('Password:'); ?></label>
				<input name="password" type="password" id="new-user-password" class="form-control" />
			</div>
			<div class="form-group">
				<label for="user-edit-password"><?php echo __('Password confirm:'); ?></label>
				<input name="password_confirm" type="password" id="new-user-password-confirm" class="form-control" />
			</div>
		</div>
		
		<div class="col-xs-12 col-md-3">
			
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Actions'); ?></h3>
				</div>
				<div class="widget-body">
					<button class="btn btn-primary">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<?php echo __('Create user'); ?>
					</button>
				</div>
			</div>
			
		</div>
	</div>
</form>
