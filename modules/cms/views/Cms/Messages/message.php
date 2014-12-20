<div class="page-header">
	<h1 data-bind="text:message.subject"><?php echo __('Read message'); ?></h1>
</div>

<div class="row">
	<div class="col-xs-12 text-right">
		<button class="btn btn-danger" data-bind="click:deleteMessage" title="<?php echo __('Delete message'); ?>">
			<span class="glyphicon glyphicon-remove"></span>
		</button>
	</div>
</div>

<div class="row">
	<div class="col-xs-1 text-right">
		<strong><?php echo __('Date:'); ?></strong>
	</div>
	<div class="col-xs-10" data-bind="text:message.nicedate"></div>
</div>

<div class="row">
	<div class="col-xs-1 text-right">
		<strong><?php echo __('From:'); ?></strong>
	</div>
	<div class="col-xs-10">
		<div data-bind="if:message.sender.type=='system'">
			<span data-bind="text:message.sender.name"></span>
		</div>
		<div data-bind="if:message.sender.type=='user'">
			<a href="#" data-bind="href:#/users/edit/'+message.sender.id,text:message.sender.name"></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-1 text-right">
		<strong><?php echo __('To:'); ?></strong>
	</div>
	<div class="col-xs-10">
		<div data-bind="if:message.recipient.type=='user'">
			<a href="#" data-bind="href:'#/users/edit/'+message.recipient.id,text:message.recipient.name"></a>
		</div>
		<div data-bind="if:message.recipient.type=='role'">
			<a href="#" data-bind="href:'#/users?role='+message.recipient.name,text:message.recipient.name"></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-1 text-right">
		<strong><?php echo __('Message:'); ?></strong>
	</div>
	<div class="col-xs-10" data-bind="text:message.message"></div>
</div>
