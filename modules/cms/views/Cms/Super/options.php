<div class="page-header">
	<h1>Manage settings</h1>
</div>

<div class="row">
	<div class="col-xs-9">
		<ul class="widget-list" data-bind="foreach:groups">
			<li class="widget block">
				<div class="widget-header">
					<div class="pull-left widget-tool widget-tool-left">
						<a href="#" data-bind="click:function(){isopen(!isopen());}">
							<span class="glyphicon" data-bind="css:{'glyphicon-chevron-right':!isopen(),'glyphicon-chevron-down':isopen()}"></span>
						</a>
					</div>
					<h3 class="widget-title" data-bind="text:display"></h3>
					<div class="widget-tool pull-right">
						<a data-bind="click:$parent.deleteContent" href="#" class="block-deleter" title="<?php echo __('Delete'); ?>">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</div>
				</div>
				<div class="widget-body" data-bind="visibleSlide:isopen()">
					<table class="table">
						<tbody data-bind="foreach:options">
							<tr>
								<td>
									<div class="form-group">
										<label>Title</label>
										<input type="text" class="form-control" data-bind="value:title" />
									</div>
								</td>
								<td>
									<div class="form-group">
										<label>Key</label>
										<input type="text" class="form-control" data-bind="value:key" />
									</div>
								</td>
								<td>
									<label>Type</label>
									<select data-bind="value:type" class="form-control">
										<option value="text">Textfield</option>
										<option value="textarea">Textarea</option>
										<option value="richtext">Richtext textarea</option>
										<option value="email">Email</option>
									</select>
								</td>
								<td>
									<label>Description</label>
									<textarea data-bind="value:description" class="form-control"></textarea>
								</td>
								<td>
									<label>Editable?</label>
									<select data-bind="value:editable" class="form-control">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</td>
								<td class="centeredtd">
									<a href="#" class="label label-danger" data-bind="click:$parent.deleteoption">
										<span class="glyphicon glyphicon-trash"></span>
									</a>
								</td>
							</tr>
						</tbody>
					</table>
					<p>
						<h4>Add new option:</h4>
						<table class="table">
							<tr>
								<td>
									<div class="form-group">
										<label>Title</label>
										<input data-bind="value:newoptiontitle" type="text" class="form-control" />
									</div>
								</td>
								<td>
									<div class="form-group">
										<label>Key</label>
										<input data-bind="value:newoptionkey" type="text" class="form-control" />
									</div>
								</td>
								<td>
									<label>Type</label>
									<select data-bind="value:newoptiontype" class="form-control">
										<option value="text">Textfield</option>
										<option value="textarea">Textarea</option>
										<option value="richtext">Richtext textarea</option>
										<option value="email">Email</option>
									</select>
								</td>
								<td>
									<label>Description</label>
									<textarea data-bind="value:newoptionsdescription" class="form-control"></textarea>
								</td>
								<td>
									<label>Editable?</label>
									<select class="form-control">
										<option value="1">Yes</option>
										<option value="0">No</option>
									</select>
								</td>
								<td>
									
								</td>
							</tr>
						</table>
						<a href="#" data-bind="click:addoption" class="btn btn-primary">
							<span class="fa fa-plus"></span>
							Add option
						</a>
					</p>
				</div>
			</li>
		</ul>
	</div>
	<div class="col-xs-3">
		Actions
	</div>
</div>


