<div class="page-header">
	<h1><?php echo __('Users'); ?></h1>
</div>

<script type="text/javascript">
	var typeaheadroles = [];
<?php
	if((bool)$roles->count())
	{
		foreach($roles as $role)
		{
			echo 'typeaheadroles.push({id:'.$role->id.',title:"'.__($role->name).'"});';
		}
	}
?>
</script>

<div class="row">
	
	<script type="text/html" id="users-role-selecter-template">
		<span data-bind="if:$parent.role!=realname">
			<a href="#" data-bind="attr:{href:'#/users?role='+realname,title:description},tooltip:{title:description}">
				<span data-bind="text:name()"></span>
				<small data-bind="text:' ('+userCount()+')'"></small>
			</a>
			<span data-bind="if:($index()<$parent.roles().length-1)">| </span>
		</span>
		<span data-bind="if:$parent.role==realname">
			<strong>
				<span data-bind="text:name()"></span>
				<small data-bind="text:' ('+userCount()+')'"></small>
			</strong>
			<span data-bind="if:($index()<$parent.roles().length-1)">| </span>
		</span>
	</script>
	
	<div class="col-xs-12">
		<span data-bind="if:role!=''">
			<a href="#/users" title="<?php echo __('All'); ?>">
				<span><?php echo __('All') ?></span>
				<small data-bind="text:'('+userCount()+')'"></small>
			</a>
			<span>|</span>
		</span>
		<span data-bind="if:role==''">
			<strong>
				<span><?php echo __('All') ?></span>
				<small data-bind="text:'('+userCount()+')'"></small>
			</strong>
			<span>| </span>
		</span>
		
		<span data-bind="template:{name:'users-role-selecter-template',foreach:roles}"></span>
		|
		<small>
			<a href="#/users/roles" title="<?php echo __('Edit roles'); ?>">
				<span class="glyphicon glyphicon-pencil"></span>
			</a>
		</small>
		
	</div>
</div>

<div class="text-right">
	<a href="#/users/new" class="btn btn-primary">
		<span class="glyphicon glyphicon-plus"></span>
		<?php echo __('Add new'); ?>
	</a>
</div>

<div class="row">
	<div class="col-xs-12">
		<table class="table" data-bind="if:!loading()">
			<thead>
				<tr>
					<th><?php echo __('Name'); ?></th>
					<th><?php echo __('E-mail'); ?></th>
					<th><?php echo __('Roles'); ?></th>
					<th><?php echo __('Created'); ?></th>
				</tr>				
			</thead>
			<tbody data-bind="foreach:users">
				<tr>
					<td>
						<a href="#" class="imglink" data-bind="attr:{href:'#/users/edit/'+id,title:name}">
							<img src="" data-bind="src:gravatar.mini()" />
						</a>
						<a href="#" data-bind="attr:{href:'#/users/edit/'+id,title:name}">
							<span data-bind="text:name"></span>
						</a>
					</td>
					<td data-bind="text:email"></td>
					<td>
						<ul data-bind="foreach:roles" class="tags">
							<li class="tag">
								<a href="#" data-bind="text:name,attr:{href:'#/users?role='+realname}"></a>
							</li>
						</ul>
					</td>
					<td data-bind="text:created.formatted"></td>
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

