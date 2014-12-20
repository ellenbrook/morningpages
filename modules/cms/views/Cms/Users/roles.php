<div class="page-header">
	<h1><?php echo __('Edit user roles'); ?></h1>
</div>

<div class="row">
	<div class="col-xs-12">
		<button class="btn btn-primary" data-bind="click:showAddRoleModal">
			<span class="glyphicon glyphicon-plus"></span>
			<?php echo __('Add new'); ?>
		</button>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<table class="table" data-bind="if:!loading()">
			<thead>
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('Description'); ?></th>
					<th><?php echo __('Users'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>				
			</thead>
			<tbody data-bind="foreach:roles">
				<tr>
					<td>
						<div data-bind="if:!deleteable">
							<span data-bind="text:name()"></span>
						</div>
						<div data-bind="if:deleteable">
							<input type="text" class="form-control" data-bind="value:name" />
						</div>
					</td>
					<td>
						<div data-bind="if:!deleteable">
							<span data-bind="text:description()"></span>
						</div>
						<div data-bind="if:deleteable">
							<textarea class="form-control" data-bind="value:description"></textarea>
						</div>
					</td>
					<td>
						<a href="#" data-bind="attr:{href:'#/users?role='+name()},text:userCount()"></a>
					</td>
					<td>
						<div data-bind="if:deleteable">
							<a href="#" data-bind="click:$root.deleteRole" class="btn btn-danger btn-sm">
								<span class="glyphicon glyphicon-remove"></span>
								Slet
							</a>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div data-bind="loader:loading()"></div>
	</div>
</div>

<div class="modal" id="users-add-role-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo __('Add role'); ?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="users-add-role-modal-name"><?php echo __('Name:'); ?></label>
					<input type="text" class="form-control" id="users-add-role-modal-name" />
				</div>
				<div class="form-group">
					<label for="users-add-role-modal-description"><?php echo __('Description:'); ?></label>
					<textarea id="users-add-role-modal-description" class="form-control"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>
				<button type="button" class="btn btn-primary" data-bind="click:addRole"><?php echo __('Add role'); ?></button>
			</div>
		</div>
	</div>
</div>

