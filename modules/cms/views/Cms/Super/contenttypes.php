<h1>Super/contenttypes</h1>

<div class="row">
	<div class="col-xs-12">
		<div data-bind="loading:testloader()"></div>
		<h2>Contenttypes</h2>
		<div data-bind="loader:loading()"></div>
		<ul class="widget-list" id="super-contenttypes" data-bind="foreach:contenttypes,sortable:sortContenttypes">
			<li class="widget block">
				<div class="widget-header">
					<div class="pull-left widget-tool widget-tool-left widget-mover">
						<span class="glyphicon glyphicon-move"></i>
					</div>
					
					<div class="pull-left widget-tool widget-tool-left">
						<a href="#" data-bind="click:toggleOpen">
							<span class="glyphicon" data-bind="css:{'glyphicon-chevron-down':open(),'glyphicon-chevron-right':!open()}"></span>
						</a>
					</div>
					<h3 class="widget-title" data-bind="text:display()"></h3>
					<div class="widget-tool pull-right">
						<a href="#" class="block-deleter" data-bind="click:$root.deleteContenttype" title="Slet denne indholdstype">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
					</div>
				</div>
				<div class="widget-body" data-bind="visible:open()">
					<div class="form-group">
						<label for="super-edit-contenttype-type">Type (machine)</label>
						<input type="text" id="super-edit-contenttype-type" data-bind="value:type" class="form-control" />
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-slug">Slug</label>
						<input type="text" id="super-edit-contenttype-slug" data-bind="value:slug" class="form-control" />
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-display">Display</label>
						<input type="text" id="super-edit-contenttype-display" data-bind="value:display" class="form-control" />
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-icon">Icon (<a href="http://fortawesome.github.io/Font-Awesome/icons/">ref</a>)</label>
						<input type="text" id="super-edit-contenttype-icon" data-bind="value:icon" class="form-control" />
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-max">Max</label>
						<input type="text" id="super-edit-contenttype-max" value="0" data-bind="value:max" class="form-control" />
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-hierarchical">Is hierarchical</label>
						<select id="super-edit-contenttype-hierarchical" data-bind="value:supports.hierarchy" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-categories">Has categories</label>
						<select id="super-edit-contenttype-categories" data-bind="value:supports.categories" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-tags">Has tags</label>
						<select id="super-edit-contenttype-tags" data-bind="value:supports.tags" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-timestamp">Has timestamp</label>
						<select id="super-edit-contenttype-timestamp" data-bind="value:supports.timestamp" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-thumbnail">Has thumbnail</label>
						<select id="super-edit-contenttype-thumbnail" data-bind="value:supports.thumbnail" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-author">Has author</label>
						<select id="super-edit-contenttype-author" data-bind="value:supports.author" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div class="form-group">
						<label for="super-edit-contenttype-author">Has status</label>
						<select id="super-edit-contenttype-author" data-bind="value:supports.status" class="form-control">
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</div>
					<div>
						<a href="#" data-bind="click:save" class="btn btn-primary">
							Gem contenttype
						</a>
					</div>
					<h4>Contenttypetypes</h4>
					<ul data-bind="foreach:contenttypetypes,sortable:saveContenttypetypesOrder" class="widget-list">
						<li class="widget block">
							<div class="widget-header">
								<div class="pull-left widget-tool widget-tool-left widget-mover">
									<span class="glyphicon glyphicon-move"></i>
								</div>
								
								<div class="pull-left widget-tool widget-tool-left">
									<a href="#" data-bind="click:toggleOpen">
										<span class="glyphicon" data-bind="css:{'glyphicon-chevron-down':open(),'glyphicon-chevron-right':!open()}"></span>
									</a>
								</div>
								<h3 class="widget-title" data-bind="text:display"></h3>
								<div class="widget-tool pull-right">
									<a href="#" class="block-deleter" title="Slet" data-bind="click:$parent.deleteContenttypetype">
										<span class="glyphicon glyphicon-trash"></span>
									</a>
								</div>
								<div class="widget-tool pull-right">
									<a href="#" title="Dupliker" data-bind="click:$parent.duplicateContenttypetype">
										<span class="fa fa-files-o"></span>
									</a>
								</div>
							</div>
							<div class="widget-body" data-bind="visible:open()">
								
								<div class="form-group">
									<label>Key (machine)</label>
									<input type="text" class="form-control" data-bind="value:key" />
								</div>
								<div class="form-group">
									<label>Display</label>
									<input type="text" class="form-control" data-bind="value:display" />
								</div>
								<div class="form-group">
									<label>Min</label>
									<input type="text" class="form-control" data-bind="value:min" />
								</div>
								<div class="form-group">
									<label>Max</label>
									<input type="text" class="form-control" data-bind="value:max" />
								</div>
								<div class="form-group">
									<a href="#" class="btn btn-primary" data-bind="click:save">
										Gem contenttypetype
									</a>
								</div>
								
								<h4>Blocktypes</h4>
								
								<script type="text/html" id="super-blocktype-template">
									<li class="widget block">
										<div class="widget-header">
											
											<div class="pull-left widget-tool widget-tool-left widget-mover">
												<span class="glyphicon glyphicon-move"></i>
											</div>
											<div class="pull-left widget-tool widget-tool-left">
												<a href="#" data-bind="click:toggleOpen">
													<span class="glyphicon" data-bind="css:{'glyphicon-chevron-down':open(),'glyphicon-chevron-right':!open()}"></span>
												</a>
											</div>
											<h3 class="widget-title">
												<span data-bind="text:display"></span>
												<small data-bind="text:type"></small>
											</h3>
											<div class="widget-tool pull-right">
												<a href="#" title="Slet" data-bind="click:$parent.deleteBlocktype">
													<span class="glyphicon glyphicon-trash"></span>
												</a>
											</div>
											<div class="widget-tool pull-right">
												<a href="#" title="Dupliker" data-bind="click:$parent.duplicateBlocktype">
													<span class="fa fa-files-o"></span>
												</a>
											</div>
										</div>
										<div class="widget-body" data-bind="visible:open()">
											<div class="form-group">
												<label>Key (machine)</label>
												<input type="text" class="form-control" data-bind="value:key" />
											</div>
											<div class="form-group">
												<label>Display</label>
												<input type="text" class="form-control" data-bind="value:display" />
											</div>
											<div class="form-group">
												<label>Min</label>
												<input type="text" class="form-control" data-bind="value:min" />
											</div>
											<div class="form-group">
												<label>Max</label>
												<input type="text" class="form-control" data-bind="value:max" />
											</div>
											<div class="form-group">
												<a href="#" data-bind="click:saveBlocktype" class="btn btn-primary">
													Gem blocktype
												</a>
											</div>
											<h4>Meta</h4>
											<div data-bind="visible:metas().length==0">
												<em>Ingen metadata</em>
											</div>
											<ul data-bind="foreach:metas" class="widget-list">
												<li class="widget block">
													<div class="widget-header">
														<div class="pull-left widget-tool widget-tool-left">
															<a href="#" data-bind="click:toggleOpen">
																<span class="glyphicon" data-bind="css:{'glyphicon-chevron-down':open(),'glyphicon-chevron-right':!open()}"></span>
															</a>
														</div>
														<h3 class="widget-title" data-bind="text:key()"></h3>
														<div class="widget-tool pull-right">
															<a href="#" class="block-deleter" title="Slet" data-bind="click:$parent.deleteMeta">
																<span class="glyphicon glyphicon-trash"></span>
															</a>
														</div>
													</div>
													<div class="widget-body" data-bind="visible:open()">
														<div class="form-group">
															<label>Key (machine)</label>
															<input type="text" class="form-control" data-bind="value:key" />
														</div>
														<div class="form-group">
															<label>Value</label>
															<input type="text" class="form-control" data-bind="value:values" />
														</div>
														<div class="form-group">
															<a href="#" class="btn btn-primary" data-bind="click:save">
																Gem meta
															</a>
														</div>
													</div>
												</li>
											</ul>
											<div class="text-right">
												<a href="#" data-bind="click:addMeta" class="btn btn-primary">
													<span class="glyphicon glyphicon-plus"></span>
													Tilføj meta
												</a>
											</div>
											<div data-bind="visible:(type=='complex')">
												<h4>Blocktypes</h4>
												<ul class="widget-list" data-bind="sortable:sortBlocktypes,template:{name:'super-blocktype-template',foreach:blocktypes}"></ul>
												<div class="text-right">
													<a href="#" class="btn btn-primary" data-bind="click:addBlocktype">
														<span class="glyphicon glyphicon-plus"></span>
														Tilføj blocktype
													</a>
												</div>
											</div>
										</div>
									</li>
								</script>
								
								<ul class="widget-list" data-bind="sortable:sortBlocktypes,template:{name:'super-blocktype-template',foreach:blocktypes}"></ul>
								
								<div class="text-right">
									<a href="#" class="btn btn-primary" data-bind="click:addBlocktype">
										<span class="glyphicon glyphicon-plus"></span>
										Tilføj blocktype
									</a>
								</div>
							</div>
						</li>
					</ul>
					<div class="text-right">
						<a href="#" class="btn btn-primary" data-bind="click:$root.showAddContenttypetypeModal">
							<span class="glyphicon glyphicon-plus"></span>
							Tilføj contenttypetype
						</a>
					</div>
				</div>
			</li>
		</ul>
		<div class="text-right">
			<a href="#" class="btn btn-primary" data-bind="click:showAddContenttypeModal">
				<span class="glyphicon glyphicon-plus"></span>
				Tilføj ny
			</a>
		</div>
	</div>
</div>

<div class="modal" id="super-add-contenttype-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tilføj indholdstype</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="super-add-contenttype-type">Type (machine)</label>
					<input type="text" id="super-add-contenttype-type" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-slug">Slug</label>
					<input type="text" id="super-add-contenttype-slug" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-display">Display</label>
					<input type="text" id="super-add-contenttype-display" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-max">Max</label>
					<input type="text" id="super-add-contenttype-max" value="0" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-hierarchical">Is hierarchical</label>
					<select id="super-add-contenttype-hierarchical" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-categories">Has categories</label>
					<select id="super-add-contenttype-categories" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-tags">Has tags</label>
					<select id="super-add-contenttype-tags" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-timestamp">Has timestamp</label>
					<select id="super-add-contenttype-timestamp" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-thumbnail">Has thumbnail</label>
					<select id="super-add-contenttype-thumbnail" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
				<div class="form-group">
					<label for="super-add-contenttype-author">Has author</label>
					<select id="super-add-contenttype-author" class="form-control">
						<option value="0">No</option>
						<option value="1">Yes</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
				<button type="button" class="btn btn-primary" data-bind="click:addContenttype">Tilføj</button>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="super-add-contenttypetype-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tilføj indholdstypetype</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="super-add-contenttypetype-type">Key (machine)</label>
					<input type="text" id="super-add-contenttypetype-key" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttypetype-type">Display</label>
					<input type="text" id="super-add-contenttypetype-display" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttypetype-min">Min</label>
					<input type="text" value="0" id="super-add-contenttypetype-min" class="form-control" />
				</div>
				<div class="form-group">
					<label for="super-add-contenttypetype-max">Max</label>
					<input type="text" value="0" id="super-add-contenttypetype-max" class="form-control" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
				<button type="button" class="btn btn-primary" data-bind="click:addContenttypetype">Tilføj</button>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="super-add-blocktype-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tilføj blocktype</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="super-add-blocktype-type">Type</label>
					<select id="super-add-blocktype-type" class="form-control">
						<option value="plaintext">Plaintext</option>
						<option value="textfield">Textfield</option>
						<option value="richtext">Richtext</option>
						<option value="selecter">Selecter</option>
						<option value="contentselecter">Contentselecter</option>
						<option value="gallery">Gallery</option>
						<option value="complex">Complex</option>
					</select>
				</div>
				<div class="form-group">
					<label for="super-add-blocktype-key">Key (machine)</label>
					<input type="text" class="form-control" id="super-add-blocktype-key" />
				</div>
				<div class="form-group">
					<label for="super-add-blocktype-display">Display</label>
					<input type="text" class="form-control" id="super-add-blocktype-display" />
				</div>
				<div class="form-group">
					<label for="super-add-blocktype-min">Min</label>
					<input type="text" class="form-control" value="0" id="super-add-blocktype-min" />
				</div>
				<div class="form-group">
					<label for="super-add-blocktype-max">Max</label>
					<input type="text" class="form-control" value="0" id="super-add-blocktype-max" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
				<button type="button" class="btn btn-primary add-blocktype">Tilføj</button>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="super-add-blocktype-meta-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Tilføj blocktype meta</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="super-add-blocktype-meta-key">Key (machine)</label>
					<input type="text" class="form-control" id="super-add-blocktype-meta-key" />
				</div>
				<div class="form-group">
					<label for="super-add-blocktype-meta-value">Value</label>
					<input type="text" class="form-control" id="super-add-blocktype-meta-value" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Luk</button>
				<button type="button" class="btn btn-primary add-blocktype-meta">Tilføj</button>
			</div>
		</div>
	</div>
</div>

