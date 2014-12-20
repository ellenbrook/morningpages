<div class="page-header">
	<h1>Navigation</h1>
</div>

<div class="row">
	
	<div class="col-xs-12 col-md-9">
		
		<div>
			<div class="form-group">
				<label for="navigation-select-menu"><?php echo __('Select menu to edit'); ?></label>
				<select class="form-control" data-bind="options:menus,optionsText:'title',optionsValue:'id',optionsCaption:'<?php _e('Select'); ?>',value:menuid"></select>
			</div>
			<div class="form-group">
				<a href="#" data-bind="click:function(){showAddMenuOptions(!showAddMenuOptions())}">
					<span class="glyphicon" data-bind="css:{'glyphicon-chevron-right':!showAddMenuOptions(),'glyphicon-chevron-down':showAddMenuOptions()}"></span>
					<?php _e('Add menu'); ?>
				</a>
			</div>
			<div data-bind="visibleSlide:showAddMenuOptions()">
				<div class="form-group">
					<label for="navigation-add-menu-title"><?php echo __('Name:'); ?></label>
					<input type="text" id="navigation-add-menu-title" placeholder="<?php _e('The name of your new menu'); ?>..." class="form-control" />
				</div>
				<div class="form-group">
					<button class="btn btn-primary" data-bind="click:addNewMenu">
						<span class="glyphicon glyphicon-plus"></span>
						<?php _e('Add menu'); ?>
					</button>
				</div>
			</div>
		</div>
		
		<hr />
		
		<div data-bind="if:menu()">
			<h2 data-bind="visible:!editingMenuTitle()">
				<span data-bind="text:menu().title()"></span>
				<small><a href="#" data-bind="click:editMenuTitle"><span class="glyphicon glyphicon-pencil"></span></a></small>
			</h2>
			<div data-bind="visible:editingMenuTitle()" class="form-group form-inline">
				<input type="text" data-bind="value:menu().title" class="form-control" />
				<button class="btn btn-primary" data-bind="click:function(){editingMenuTitle(false)}"><?php _e('Ok'); ?></button>
			</div>
			
			<script type="text/html" id="navigation-menuitem-template">
				<li class="widget">
					
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
							<span data-bind="text:linktext"></span>
							<?php /*<small data-bind="text:titletext"></small> */ ?>
							<small data-bind="visible:type=='external'">
								<span class="glyphicon glyphicon-share"></span>
							</small>
						</h3>
						<div class="widget-tool pull-right">
							<a data-bind="click:$parent.deleteMenuItem" href="#" class="block-deleter" title="Slet denne blok">
								<span class="glyphicon glyphicon-trash"></span>
							</a>
						</div>
					</div>
					
					<div class="widget-body" data-bind="visibleSlide:open()">
						<div class="form-group">
							<label><?php _e('Linktext'); ?>:</label>
							<input type="text" class="form-control" data-bind="value:linktext" />
						</div>
						<div class="form-group">
							<label><?php _e('Titletext'); ?>:</label>
							<input data-bind="value:titletext" type="text" placeholder="En kort beskrivende tekst her..." class="form-control" />
						</div>
						<div data-bind="if:type=='content'">
							<div class="original">
								<?php _e('Original'); ?>: <a href="#" data-bind="text:content.title,attr:{href:'#/content/edit/'+content.id}"></a>
							</div>
						</div>
						<div data-bind="if:type=='external'">
							<div class="form-group">
								<label><?php _e('URL'); ?>:</label>
								<input data-bind="value:url" type="text" placeholder="http://" class="form-control" />
							</div>
						</div>
					</div>
					
					<?php /*<ul class="widget-kids" data-bind="visible:children().length>0,template:{if:children().length>0,name:'navigation-menuitem-template',foreach:children}"></ul> */ ?>
					<ul class="widget-kids" data-bind="template:{name:'navigation-menuitem-template',foreach:children}"></ul>
					
				</li>
			</script>
			<ul class="widget-list" id="navigation-menu-list" data-bind="nestedsortable:true,template:{name:'navigation-menuitem-template',foreach:getRootMenuItems}"></ul>
		</div>
		
	</div>
	
	<div class="col-xs-12 col-md-3">
		<div data-bind="visible:menu()">
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php _e('Actions'); ?></h3>
				</div>
				<div class="widget-body">
					<div class="form-group">
						<label for="navigation-menu-placement"><?php _e('Placements'); ?>:</label>
						<select id="navigation-menu-placement" class="form-control" data-bind="options:menutypes,optionsText:'title',optionsValue:'id',value:(menu()?menu().menutype_id():0),optionsCaption:'<?php _e('Select placement'); ?>'"></select>
					</div>
					<div class="form-group">
						<button class="btn btn-primary" data-bind="click:saveMenu">
							<?php echo __('Save menu'); ?>
						</button>
					</div>
					<hr />
					<div class="text-right">
						<a href="#" class="btb btn-danger btn-sm" title="<?php echo __('Delete this menu') ?>" data-bind="click:deleteMenu" title="Slet menu">
							<span class="glyphicon glyphicon-trash"></span>
							<?php _e('delete'); ?>
						</a>
					</div>
				</div>
			</div>
			
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Add internal menuitem:') ?></h3>
				</div>
				<div class="widget-body">
					
					<div class="form-group">
						<label for="navigation-select-contenttype"><?php _e('Choose contenttype'); ?></label>
						<select id="navigation-select-contenttype" data-bind="value:contenttypeid" class="form-control">
							<option value="0"><?php _e('Select'); ?></option>
	<?php
							$contenttypes = ORM::factory('Contenttype')->find_all();
							if((bool)$contenttypes->count())
							{
								foreach($contenttypes as $contenttype)
								{
									echo '<option value="'.$contenttype->id.'">'.$contenttype->display.'</option>';
								}
							}
	?>
						</select>
					</div>
					
					<div data-bind="visible:contenttype()">
						<div class="form-group">
							<label for="navigation-select-content"><?php _e('Select content'); ?></label>
							<select id="navigation-select-content" class="form-control" data-bind="options:contenttype().items,optionsValue:'id',optionsText:'title',optionsCaption'<?php echo __('Select'); ?>',value:contentid"></select>
						</div>
					</div>
					
					<div data-bind="visible:contentid()" class="text-right">
						<button data-bind="click:addInternalMenuItem" class="btn btn-primary">
							<span class="glyphicon glyphicon-plus"></span>
							<?php echo __('Add to menu'); ?>
						</button>
					</div>
					
				</div>
			</div>
			
			<div class="widget">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Add external menuitem:') ?></h3>
				</div>
				<div class="widget-body">
					<div class="form-group">
						<label for="navigation-add-external-url"><?php echo __('URL (href):') ?></label>
						<input data-bind="value:externalitem().url" type="text" id="navigation-add-external-url" class="form-control" />
					</div>
					<div class="form-group">
						<label for="navigation-add-external-linktext"><?php echo __('Linktext:') ?></label>
						<input data-bind="value:externalitem().linktext" type="text" id="navigation-add-external-linktext" class="form-control" />
					</div>
					<div class="form-group">
						<label for="navigation-add-external-titletext"><?php echo __('Title:') ?></label>
						<input data-bind="value:externalitem().titletext" type="text" id="navigation-add-external-titletext" class="form-control" />
					</div>
					<div class="text-right">
						<button data-bind="click:addExternalMenuItem" class="btn btn-primary">
							<span class="glyphicon glyphicon-plus"></span>
							<?php echo __('Add to menu'); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-xs-12 col-md-9">
		
		
		
		
	</div>
	
</div>
