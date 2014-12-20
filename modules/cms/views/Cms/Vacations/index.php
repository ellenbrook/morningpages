<div class="page-header">
	<h1>Ferier</h1>
</div>
<div class="form-group text-right">
	<a href="#" data-bind="togglemodal:'vacations-add-new-modal'" class="btn btn-primary">
		<span class="glyphicon glyphicon-plus"></span>
		Tilføj ny
	</a>
</div>

<ul class="widget-list" data-bind="foreach:vacations">
	<li class="widget">
		<div class="widget-header">
			<h3 class="widget-title">
				<span data-bind="text:title"></span>:
				<span>
					<span data-bind="text:start"></span> til
					<span data-bind="text:end"></span>
				</span>
			</h3>
			<div class="widget-tool pull-right">
				<a href="#" class="block-deleter" data-bind="click:$root.deleteVacation" title="Slet">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
		</div>
	</li>
</ul>

<div class="modal" id="vacations-add-new-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tilføj ferie</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="vacations-add-new-title">Titel</label>
					<input type="text" id="vacations-add-new-title" class="form-control" />
				</div>
				<div class="form-group">
					<label for="vacations-add-new-start">Startdato (inklusiv)</label>
					<input type="text" class="form-control" id="vacations-add-new-start" data-bind="datepicker:true" />
				</div>
				<div class="form-group">
					<label for="vacations-add-new-end">Slutdato (inklusiv)</label>
					<input type="text" class="form-control" id="vacations-add-new-end" data-bind="datepicker:true" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
				<button type="button" data-bind="click:addNewVacation" class="btn btn-primary add-blocktype-meta">Tilføj</button>
			</div>
		</div>
	</div>
</div>
