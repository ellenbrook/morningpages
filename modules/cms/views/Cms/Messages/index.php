<div class="page-header">
	<h1><?php echo __('Messages'); ?></h1>
</div>
<?php /*
<ul data-bind="foreach:messages" class="widget-list">
	<li class="widget">
		<div class="widget-header">
			<div class="pull-left widget-tool widget-tool-left">
				<a href="#" data-bind="click:function(){open(!open());}">
					<span class="glyphicon" data-bind="css:{'glyphicon-chevron-right':!open(),'glyphicon-chevron-down':open()}"></span>
				</a>
			</div>
			<h3 class="widget-title">
				<a href="#" data-bind="text:subject,attr:{href:'#/messages/message/'+id}"></a>
			</h3>
			<div class="widget-tool pull-right">
				<a data-bind="click:$parent.deleteContent" href="#" class="block-deleter" title="<?php echo __('Delete'); ?>">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
			<div class="widget-tool pull-right">
				<span data-bind="text:nicedate"></span>
			</div>
		</div>
		<div class="widget-body" data-bind="visibleSlide:open()">
			
		</div>
	</li>
</ul> */ ?>

<table class="table table-striped">
	<thead>
		<tr>
			<th><input data-bind="checkall:true" type="checkbox" /></th>
			<th></th>
			<th></th>
			<th><?php echo __('Subject'); ?></th>
			<th><?php echo __('To'); ?></th>
			<th><?php echo __('Sender'); ?></th>
			<th><?php echo __('Date'); ?></th>
		</tr>
	</thead>
	<tbody data-bind="foreach:messages">
		<tr>
			<td>
				<input type="checkbox" data-bind="value:id" />
			</td>
			<td>
				<a href="#" data-bind="click:toggleRead">
					<span data-bind="css:{'fa-envelope':read()=='0','fa-envelope-o':read()=='1'}" class="fa"></span>
				</a>
			</td>
			<td>
				<span data-bind="visible:hasreply" class="glyphicon glyphicon-share-alt"></span>
			</td>
			<td>
				<a href="#" data-bind="attr:{href:'#/messages/message/'+id}">
					<span data-bind="if:read()=='1'">
						<span data-bind="text:subject"></span>
					</span>
					<span data-bind="if:read()=='0'">
						<strong data-bind="text:subject"></strong>
					</span>
				</a>
			</td>
			<td>
				<div data-bind="if:recipient.type=='user'">
					<a href="#" data-bind="href:'#/users/edit/'+recipient.id,text:recipient.name"></a>
				</div>
				<div data-bind="if:recipient.type=='role'">
					<a href="#" data-bind="href:'#/users?role='+recipient.name,text:recipient.name"></a>
				</div>
			</td>
			<td>
				<div data-bind="if:sender.type=='system'">
					<span data-bind="text:sender.name"></span>
				</div>
				<div data-bind="if:sender.type=='user'">
					<a href="#" data-bind="href:#/users/edit/'+sender.id,text:sender.name"></a>
				</div>
			</td>
			<td>
				<span data-bind="text:nicedate"></span>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th><input data-bind="checkall:true" type="checkbox" /></th>
			<th></th>
			<th></th>
			<th><?php echo __('Subject'); ?></th>
			<th><?php echo __('To'); ?></th>
			<th><?php echo __('Sender'); ?></th>
			<th><?php echo __('Date'); ?></th>
		</tr>
	</tfoot>
</table>
