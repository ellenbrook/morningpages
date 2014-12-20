<?php
	/*echo cms::breadcrumbs(array(
		$content->contenttype->display => cms::url('content/index/'.$content->contenttype_id),
		'Rediger '.$content->title() => ''
	));*/
	
?>

<div class="page-header">
	<div class="pull-right">
		<a class="btn btn-default" href="<?php echo $content->url(); ?>" title="<?php echo __('See content') ?>" target="_blank">
			<span class="glyphicon glyphicon-eye-open"></span> <?php echo __('See content') ?>
		</a>
	</div>
	<h1>
		<?php echo __('Edit :contenttypetype_display', array(':contenttypetype_display' => strtolower($content->contenttypetype->display))); ?>
		<small>
			<a href="<?php echo cms::url('#/content/new/'.$content->contenttype_id.'/'.$content->contenttypetype_id); ?>" class="btn btn-default btn-sm" title="<?php echo __('Add new :contenttype', array(':contenttype'=>strtolower($content->contenttypetype->display))) ?>">
				<?php echo __('Add new'); ?>
			</a>
		</small>
	</h1>
</div>

<?php if($content->splittest != 0): ?>
	<p>
		<em>This is a splittest of <a href="#/content/edit/<?php echo $content->splittestparent; ?>"><?php echo $content->splittestparent->title; ?></a></em>
	<p>
<?php endif; ?>

<form action="content/edit/<?php echo $content->id; ?>" role="form" method="post">
	<div class="row">
		
		<div class="col-xs-9">
			<ul class="nav nav-tabs" data-bind="tabs">
				<li class="active">
					<a href="#infotab">
						<span class="glyphicon glyphicon-folder-close"></span>
						<?php echo __('Content') ?>
					</a>
				</li>
				<li>
					<a href="#seotab">
						<span class="glyphicon glyphicon-search"></span>
						<?php echo __('SEO'); ?>
					</a>
				</li>
				<li>
					<a href="#socialtab">
						<span class="fa fa-share-alt"></span>
						<?php echo __('Social share'); ?>
					</a>
				</li>
				<?php if($content->splittest == 0): ?>
					<li>
						<a href="#splittests">
							<span class="fa fa-code-fork"></span>
							<?php _e('Split tests'); ?>
						</a>
					</li>
				<?php endif; ?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="infotab">
					<div class="form-group">
						<label for="content-title"><?php echo __('Title'); ?></label>
						<input type="text" class="form-control" name="title" id="content-title" value="<?php echo $content->title; ?>" />
					</div>
					<hr />
					
					<script type="text/html" id="content-edit-block">
						<div class="widget block">
							<div class="widget-header">
								
								<div class="pull-left widget-tool widget-tool-left widget-mover">
									<span class="glyphicon glyphicon-move"></i>
								</div>
								<div class="pull-left widget-tool widget-tool-left">
									<a href="#" data-bind="click:toggleOpen">
										<span class="glyphicon" data-bind="css:{'glyphicon-chevron-down':collapsed()==0,'glyphicon-chevron-right':collapsed()==1}"></span>
									</a>
								</div>
								<h3 class="widget-title pull-left">
									<span data-bind="text:blocktype.display"></span>
									<small data-bind="text:excerpt()"></small>
								</h3>
								<div class="widget-tool pull-right">
									<a data-bind="click:$parent.removeBlock" href="#" class="block-deleter" title="<?php echo __('Delete this block') ?>">
										<span class="glyphicon glyphicon-trash"></span>
									</a>
								</div>
								
							</div>
							<div class="widget-body" data-bind="visibleSlide:collapsed()=='0'">
								
								<div data-bind="loader:loading()"></div>
								
								<?php // Complex ?>
								<div data-bind="if:blocktype.type=='complex'&&availableBlocktypes().length>0">
									<div class="form-inline">
										<select class="blocktypes form-control" style="vertical-align: top;" data-bind="options:availableBlocktypes(),optionsText:'display',optionsValue:'id',optionsCaption:'<?php echo __('Select contenttype') ?>'">
										</select>
										<button class="btn btn-primary blocktypes-btn" data-bind="click:addBlock">
											<span class="glyphicon glyphicon-plus"></span> <?php echo __('Add'); ?>
										</button>
									</div>
									<hr />
								</div>
								
								<?php // Plaintext ?>
								<div data-bind="if:blocktype.type=='plaintext'">
									<textarea class="form-control" data-bind="attr:{name:formelementname()},value:value()"></textarea>
								</div>
								
								<?php // Textfield ?>
								<div data-bind="if:blocktype.type=='textfield'">
									<input type="text" data-bind="attr:{name:formelementname()},value:value()" class="form-control" />
								</div>
								
								<?php // Richtext ?>
								<div data-bind="if:blocktype.type=='richtext'">
									<textarea class="form-control tinymce" data-bind="attr:{name:formelementname()},value:value(),tinymce:true"></textarea>
								</div>
								
								<?php // Selecter ?>
								<div data-bind="if:blocktype.type=='selecter'">
									<select class="form-control" data-bind="attr:{name:formelementname()},value:value(),options:blocktype.getMeta('values'),optionsCaption:'<?php echo __('Select..') ?>'"></select>
								</div>
								
								<?php // Contentselecter ?>
								<div data-bind="if:blocktype.type=='contentselecter'">
									<select class="form-control" data-bind="attr:{name:formelementname()},value:value(),options:blocktype.contents(),optionsCaption:'<?php echo __('Select content') ?>',optionsValue:'id',optionsText:'title'"></select>
								</div>
								
								<?php // Gallery ?>
								<div data-bind="if:blocktype.type=='gallery'">
<?php
									//echo files::filebrowser();
?>
									<div class="filebrowserwrap">
										<button data-bind="filebrowserbtn:{callback:addfiles,limit:blocktype.filelimit()}" type="button" class="btn filebrowserbtn">
											<span class="glyphicon glyphicon-file"></span>
											<span data-bind="if:blocktype.filelimit()==1"><?php echo __('Select file') ?></span>
											<span data-bind="if:blocktype.filelimit()!=1">
												<?php echo __('Select files') ?>
												<small data-bind="visible:blocktype.filelimit()>0">(<?php echo __('max') ?> <span data-bind="text:blocktype.filelimit()"></span>)</small>
											</span>
										</button>
									</div>
									<div class="filebrowserwrap">
										<input type="hidden" data-bind="attr:{name:formelementname()}" />
										<ul class="thumbnails row" data-bind="foreach:files,visible:files().length>0">
											<li class="col-xs-2">
												<div class="thumbnail">
													<input type="hidden" data-bind="attr:{'name':$parent.fileformelementname(id, 'id')},value:id" value="" data-bind="value:id()" />
													<div class="closer">
														<a href="#" class="close filebrowser-file-deleter" data-bind="click:$parent.removeFile" title="Fjern">
															<span class="glyphicon glyphicon-remove"></span>
														</a>
													</div>
													<a href="#" data-bind="css:{fancybox:image()}" rel="gallery">
														<img src="#" data-bind="attr{src:medium()}" />
													</a>
													<div class="caption">
														<div class="form-group">
															<textarea data-bind="value:description(),attr:{'name':$parent.fileformelementname(id, 'description')}" placeholder="<?php echo __('Description..'); ?>" name=""></textarea>
														</div>
														<div class="form-group">
															<input type="text" value="" class="form-control" data-bind="value:alt(),attr:{'name':$parent.fileformelementname(id, 'alt')}" placeholder="<?php echo __('Alt text..') ?>" />
														</div>
													</div>
												</div>
											</li>
										</ul>
									</div>
								</div>
								
								<div class="kids" data-bind="sortable:sortChildBlocks,template:{name:'content-edit-block',foreach:blocks}"></div>
								
							</div>
						</div>
					</script>
					
					<div data-bind="loader:loading()"></div>
					
					<div id="contentblocks" class="root" data-bind="template:{name:'content-edit-block',foreach:blocks},sortable:sortRootBlocks">
						
					</div>
					
				</div>
				
				<div class="tab-pane" id="seotab">
					<div class="control-group">
						<?php echo __('None of these fields are mandatory. The system will automatically fill out empty fields') ?>
					</div>
					
					<div class="form-group">
						<label for="content-slug"><?php echo __('Slug'); ?></label>
						<div class="input-group">
							<span class="input-group-addon">
<?php
								$url = '';
								if($content->contenttype->slug != '')
								{
									$url = $content->contenttype->slug.'/';
								}
								if($content->contenttype->supports('categories'))
								{
									$firstcat = $content->categories->find();
									$url .= $firstcat->guid;
								}
								else
								{
									if($content->parent != 0)
									{
										$parent = ORM::factory('Content', $content->parent);
										$url = $parent->guid;
									}
								}
								$url = URL::site($url,'http');
								if(substr($url, strlen($url)-1,1)!='/')
								{
									$url .= '/';
								}
?>
								<span class="add-on"><?php echo $url; ?></span>
							</span>
							<input type="text" class="form-control" name="slug" id="content-slug" value="<?php echo $content->slug; ?>" />
						</div>
					</div>
					
					<div class="form-group">
<?php
						$sitetitle = cms::option('sitename');
						$length = 70 - strlen($sitetitle) - 3 - strlen($content->pagetitle);
						$metalength = 155 - strlen($content->metadescription);
?>
						<label for="content-pagetitle"><?php echo __('Pagetitle'); ?> (<span id="page-title-count" data-available="<?php echo $length; ?>"><?php echo $length; ?></span>)</label>
						<input type="text" name="pagetitle" class="form-control" id="content-pagetitle" value="<?php echo $content->pagetitle; ?>" />
					</div>
					
					<div class="form-group">
						<label for="content-metadescription"><?php echo __('Metadescription') ?> (<span id="page-meta-description-count"><?php echo $metalength ?></span>)</label>
						<textarea name="metadescription" class="form-control" id="content-metadescription"><?php echo $content->metadescription; ?></textarea>
					</div>
					
					<div class="form-group">
						<label for="content-metakeywords"><?php echo __('Metakeywords') ?></label>
						<textarea name="metakeywords" class="form-control" id="content-metakeywords"><?php echo $content->metakeywords; ?></textarea>
					</div>
					
					<?php /*<div class="control-group">

						<label for="content-canonical">Canonical URL</label>

						<input type="text" name="canonical" disabled class="span10" id="content-canonical" value="<?php echo $content->canonical; ?>" />

					</div>*/ ?>					

				</div>
				
				<div class="tab-pane" id="socialtab">
					<div class="form-group">
						<label for="content-og_title">og:title</label>
						<input type="text" name="og_title" id="content-og_title" class="form-control" value="<?php echo $content->og_title; ?>" />
					</div>
					<div class="form-group">
						<label for="content-og_type">og:type</label>
						<select name="og_type" id="content-og_type" class="form-control">
							<option value="website"<?php echo ($content->og_type=='website'?' selected="selected"':''); ?>><?php echo __('website'); ?></option>
							<option value="article"<?php echo ($content->og_type=='article'?' selected="selected"':''); ?>><?php echo __('article'); ?></option>
							<option value="book"<?php echo ($content->og_type=='book'?' selected="selected"':''); ?>><?php echo __('book'); ?></option>
							<option value="profile"<?php echo ($content->og_type=='profile'?' selected="selected"':''); ?>><?php echo __('profile'); ?></option>
							<option value="music.song"<?php echo ($content->og_type=='music.song'?' selected="selected"':''); ?>><?php echo __('music.song'); ?></option>
							<option value="music.album"<?php echo ($content->og_type=='music.album'?' selected="selected"':''); ?>><?php echo __('music.album'); ?></option>
							<option value="music.playlist"<?php echo ($content->og_type=='music.playlist'?' selected="selected"':''); ?>><?php echo __('music.playlist'); ?></option>
							<option value="music.radio_station"<?php echo ($content->og_type=='music.radio_station'?' selected="selected"':''); ?>><?php echo __('music.radio_station'); ?></option>
							<option value="video.movie"<?php echo ($content->og_type=='video.movie'?' selected="selected"':''); ?>><?php echo __('video.movie'); ?></option>
							<option value="video.episode"<?php echo ($content->og_type=='video.episode'?' selected="selected"':''); ?>><?php echo __('video.episode'); ?></option>
							<option value="video.tv_show"<?php echo ($content->og_type=='video.tv_show'?' selected="selected"':''); ?>><?php echo __('video.tv_show'); ?></option>
							<option value="video.other"<?php echo ($content->og_type=='video.other'?' selected="selected"':''); ?>><?php echo __('video.other'); ?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="content-og_title">og:description</label>
						<textarea name="og_description" id="content-og_description" class="form-control"><?php echo $content->og_description; ?></textarea>
					</div>
					<ul>
						<li>
							<a href="http://ogp.me/">http://ogp.me/</a>
						</li>
						<li>
							<a href="https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content#tags">https://developers.facebook.com/docs/opengraph/howtos/maximizing-distribution-media-content#tags</a>
						</li>
						<li>
							<a href="http://moz.com/blog/silly-marketer-title-tags-are-for-robots">http://moz.com/blog/silly-marketer-title-tags-are-for-robots</a>
						</li>
					</ul>
					
					
					
				</div>
				
				<?php if($content->splittest == 0): ?>
					<div class="tab-pane" id="splittests">
						<table class="table">
							<tbody data-bind="foreach:splittests">
								<tr>
									<td>
										<a href="#" data-bind="text:title,attr{href:'#/content/edit/'+id}"></a>
									</td>
									<td></td>
								</tr>
							</tbody>
						</table>
						<hr />
						<h3>Create new splittest</h3>
						<div class="form-group">
							<label for="new-splittest-title">Title</label>
							<input type="text" class="form-control" id="new-splittest-title" />
						</div>
						<a href="#" data-bind="click:createSplittest" class="btn btn-default">
							<span class="fa fa-plus"></span>
							Create
						</a>
						
					</div>
				<?php endif; ?>
			</div>
			
			
			
		</div>
	
		<div class="col-xs-3">
			<div class="widget" id="contentcontrols" data-id="<?php echo $content->id; ?>" data-contenttypeid="<?php echo $content->contenttype_id; ?>">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Actions'); ?></h3>
				</div>
				<div class="widget-body">
					<?php if($content->contenttype->supports('timestamp')): ?>
						<div class="form-group">
							<label for="publishdate"><?php echo __('Publishdate') ?>:</label>
							<div class="form-group">
								<input type="text" class="form-control" name="publishdate" data-bind="datepicker:true" value="<?php echo date('d-m-Y', ($content->published!=0?$content->published:time())); ?>" />
							</div>
						</div>
					<?php endif; ?>
					<?php if($content->contenttype->supports('status')): ?>
						<div class="form-group">
							<label for="content-status"><?php echo __('Status'); ?></label>
							<select name="status" class="form-control" id="content-status">
								<option value="active"<?php echo ($content->status=='active'?' selected="selected"':'') ?>><?php echo __('Published'); ?></option>
								<option value="draft"<?php echo ($content->status=='draft'?' selected="selected"':'') ?>><?php echo __('Draft') ?></option>
							</select>
						</div>
					<?php endif; ?>
<?php
					if($content->contenttype->supports('hierarchy'))
					{
?>
						<div class="form-group">
							<label for="content-parent"><?php echo __('Parent'); ?></label>
							<select name="parent" class="form-control" id="content-parent">
								<option value="0"<?php echo ($content->parent==0?' selected="selected"':''); ?>>- <?php echo __('No parent'); ?> -</option>
<?php
								function loop_content_rows($conts, $content, $level = 0)
								{
									foreach($conts as $cont)
									{
										if($cont->id != $content->id)
										{
											echo '<option style="padding-left:'.($level*10).'px" value="'.$cont->id.'"'.($content->parent==$cont->id?' selected="selected"':'').'>'/*.str_repeat('- ', $level)*/.$cont->title().'</option>';
											$kids = ORM::factory('content')->where('parent','=',$cont->id)->find_all();
											if((bool)$kids->count())
											{
												loop_content_rows($kids, $content, $level+1);
											}
										}
									}
								}
								$conts = ORM::factory('content')
									->where('parent','=',0)
									->where('contenttype_id', '=', $content->contenttype_id)
									->find_all();
								loop_content_rows($conts, $content);
?>
							</select>
						</div>
<?php
					}
?>
					<div class="control-group">
						<button name="publishbtn" value="yep" class="btn btn-primary">
							<span class="glyphicon glyphicon-floppy-disk"></span>
							<?php _e('Save') ?>
						</button>
					</div>
					<hr />
					<div class="control-group">
						<div class="row">
							<div class="col-xs-6">
								<a href="#" class="btn btn-default" title="<?php echo __('Create copy') ?>" data-bind="click:copyContent">
									<span class="fa fa-files-o"></span>
								</a>
							</div>
							<div class="col-xs-6 text-right">
								<a id="content-delete-btn" href="#content/delete/<?php echo $content->id; ?>" title="<?php echo __('Delete this content') ?>" class="btn btn-danger btn-sm">
									<span class="glyphicon glyphicon-trash"></span>
									<?php echo __('delete'); ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		
<?php
			if($content->contenttype->supports('categories'))
			{
?>
				<div class="widget">
					<div class="widget-header">
						<h3 class="widget-title"><?php echo __('Categories') ?></h3>
					</div>
					<div class="widget-body">
						<div class="categorylist" id="categorylist">
<?php
							$view = view::factory('Cms/Content/categories/checkboxes');
							$view->content = $content;
							echo $view->render();
?>
						</div>
						<button class="btn" id="add-category-btn" data-contenttypeid="<?php echo $content->contenttype_id; ?>">
							<i class="icon-plus"></i>
							<?php echo __('New category') ?>
						</button>
					</div>
				</div>
<?php
			}
			if($content->contenttype->supports('thumbnail'))
			{
?>
				<div class="widget">
					<div class="widget-header">
						<h3 class="widget-title"><?php echo __('Thumbnail'); ?></h3>
					</div>
					<div class="widget-body">
						<input type="hidden" name="thumbnail" data-bind="value:thumbnail()?thumbnail().id:0" />
						<div data-bind="if:thumbnail()">
							<img data-bind="attr:{src:thumbnail().thumbnail()}" />
						</div>
						<div data-bind="ifnot:thumbnail()">
							<em><?php echo __('No thumbnail selected'); ?></em>
						</div>
						
						<div>
							<a href="#" class="btn btn-primary" data-bind="filebrowserbtn:{limit:1,callback:selectthumbnail}">
								<?php echo __('Select thumbnail'); ?>
							</a>
							<span data-bind="visible:thumbnail()">(<a href="#" data-bind="click:clearthumbnail"><?php echo __('clear') ?></a>)</span>
						</div>
						
					</div>
				</div>
<?php
			}
			if($content->contenttype->supports('tags'))
			{
?>
				<div class="widget">
					<div class="widget-header">
						<h3 class="widget-title"><?php echo __('Tags'); ?></h3>
					</div>
					<div class="widget-body">
						<ul class="tags" data-bind="foreach:tags">
							<li class="tag">
								<span data-bind="text:tag"></span>
								<a href="#" class="remove" title="<?php echo __('Remove'); ?>" data-bind="click:$root.removeTag">X</a>
							</li>
						</ul>
						<input type="text" data-bind="typeahead:{data:alltags, callback:addTag, enterAction:addTag}" autocomplete="off" class="form-control" />
						<?php echo __('Add a tag with the enter key'); ?>
					</div>
				</div>
<?php
			}
?>
			<div class="widget" data-bind="visible:availableBlocktypes().length>0">
				<div class="widget-header">
					<h3 class="widget-title"><?php echo __('Add new contentblock'); ?></h3>
				</div>
				<div class="widget-body form-inline">
					<script type="text/html" id="content-edit-blocktypes-option">
						<option data-bind="value:id,text:display"></option>
					</script>
					<select class="form-control blocktypes" style="vertical-align: top;" data-bind="options:availableBlocktypes(),optionsText:'display',optionsValue:'id',optionsCaption:'<?php echo __('Select contenttype') ?>'"></select>
					<?php /* <select class="form-control blocktypes" style="vertical-align: top;" id="blocktypes">
						<option value="0">Vælg indholdstype..</option>
<?php
						$blocktypes = $content->contenttype->blocktypes
							->where('parent','=',0)
							->and_where_open()
							->where('contenttypetype_id','=','0')
							->or_where('contenttypetype_id','=',$content->contenttypetype->id)
							->and_where_close()
							->find_all();
						if((bool)$blocktypes->count())
						{
							foreach($blocktypes as $blocktype)
							{
								$numblocks = ORM::factory('Block')->where('content_id','=',$content->id)->where('blocktype_id','=',$blocktype->id)->count_all();
								echo '<option value="'.$blocktype->id.'" data-max="'.$blocktype->max.'" class="'.(($blocktype->max>0&&($numblocks>=$blocktype->max))?'hide':'').'">'.$blocktype->display.'</option>';
							}
						}
?>
					</select>*/ ?>
					
					<button class="btn btn-primary blocktypes-btn" data-bind="click:addBlock,disable:loading()" data-contentid="<?php echo $content->id; ?>">
						<span class="glyphicon glyphicon-plus"></span>
						<?php echo __('Add'); ?>
					</button>
				</div>
			</div>
			
		</div>
		
		
	
	</form>

</div>

<div class="modal hide fade" id="categorymodal">
	<div class="modal-header">
		<a href="#" class="close" data-dismiss="modal">x</a>
		<h3><?php _e('New category') ?></h3>
	</div>
	<div class="modal-body form-horizontal">
		<div class="control-group">
			<label for="new-category-name"><?php _e('Name') ?></label>
			<input type="text" id="new-category-name" class="input" />
		</div>
		<div class="control-group">
			<label for="new-category-parent"><?php _e('Parent'); ?></label>
			<select id="new-category-parent">
<?php
			if($content->contenttype->supports('categories'))
			{
				$view = view::factory('Cms/Content/categories/dropdown');
				$view->content = $content;
				echo $view->render();
			}
?>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal"><?php _e('Cancel') ?></button>
		<button class="btn btn-primary" data-contentid="<?php echo $content->id; ?>" id="new-category-go"><?php _e('Create'); ?></button>
	</div>
</div>
