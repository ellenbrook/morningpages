<div class="page-header">
	<div class="row">
		<div class="col-xs-9">
			<h1 data-bind="text:dashboard()?dashboard().name:''"></h1>
		</div>
		<div class="col-xs-3 form-inline text-right">
			<?php /*<select class="form-control" data-bind="event:{change:changeDashboard},options:dashboards(),optionsCaption:'Vælg kontrolpanel',optionsText:'name',optionsValue:'id',value:currentDashboardId"></select> */ ?>
			<select class="form-control" data-bind="event:{change:changeDashboardEvent},options:dashboards(),value:dashboard().id,optionsText:'name',optionsValue:'id'"></select>
			<a href="#" title="<?php echo __('Add new panel') ?>" data-bind="click:createDashboard" class="btn btn-sm btn-default">
				<span class="glyphicon glyphicon-plus"></span>
			</a>
			<a href="#" data-bind="click:deleteDashboard" title="<?php echo __('Delete this panel') ?>" class="btn btn-sm btn-danger">
				<span class="glyphicon glyphicon-trash"></span>
			</a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-4">
		Which
	</div>
	<div class="col-xs-4">
		stats
	</div>
	<div class="col-xs-4">
		here?
	</div>
</div>

<div class="row" id="widget-actions">
	<div class="col-xs-3 col-xs-offset-9 text-right">
		<form class="form-inline">
			<div class="form-group">
				<select class="form-control widget-types" data-bind="">
					<option value="0" selected="selected"><?php echo __('Select widget') ?></option>
<?php
					$widgets = ORM::factory('Widgettype')->find_all();
					if((bool)$widgets->count())
					{
						foreach($widgets as $widget)
						{
							echo '<option value="'.$widget->id.'">'.$widget->display.'</option>';
						}
					}
?>
				</select>
				<button class="btn btn-primary" data-bind="click:addWidget"><?php echo __('Add') ?></button>
			</div>
		</form>
	</div>
</div>

<div class="row" data-bind="with:dashboard()">
	
	<div data-bind="foreach:widgets,sortablegrid:saveWidgetsOrder" id="controlpanel-widgets">
		<div class="col-md-6 block panelblock" data-bind="initWidget:load,event:{onload:function(){console.log('ready')}}">
			<div class="widget">
				<div class="widget-header">
					<div class="pull-left widget-tool widget-tool-left widget-mover">
						<span class="glyphicon glyphicon-move"></i>
					</div>
					<h3 class="widget-title" data-bind="text:widgettype.display"></h3>
					<div class="widget-tool pull-right">
						<a href="#" class="block-deleter" data-bind="click:$parent.deleteWidget" title="Slet">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</div>
				</div>
				<div class="widget-body">
					<div data-bind="if:loading()">
						<img src="<?php echo cms::url('media/img/ajax-loader-widget.gif'); ?>" alt="Loader" />
					</div>
					<div class="widget-content-holder">
						
						<?php // Popular content ?>
						<div data-bind="if:widgettype.type=='popularcontent'">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Indhold</th>
										<th>Type</th>
										<th>Hits</th>
									</tr>
								</thead>
								<tbody data-bind="foreach:content">
									<tr>
										<td>
											<a href="#" data-bind="text:title,attr:{href:'#/content/edit/'+id}"></a>
										</td>
										<td data-bind="text:type"></td>
										<td data-bind="text:hits"></td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<?php // Errorpages ?>
						<div data-bind="if:widgettype.type=='errorpages'">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Type</th>
										<th>URL</th>
										<th>Dato</th>
									</tr>
								</thead>
								<tbody data-bind="foreach:errors">
									<tr>
										<td data-bind="text:type"></td>
										<td data-bind="text:url"></td>
										<td data-bind="text:when"></td>
									</tr>
								</tbody>
							</table>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
