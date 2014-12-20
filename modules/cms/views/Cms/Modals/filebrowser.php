<?php
	$allfiles = ORM::factory('File')->find_all();
?>
<div class="modal fullmodal" id="filebrowsermodal" data-totalfiles="<?php echo $allfiles->count(); ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="row">
				<div class="col-xs-2 leftbar">
					
					<div class="filebrowserdropzone">
						<div class="file-uploader" data-limit="1" id="filebrowser-upload-btn">
							<span class="btn btn-default fileinput-button">
								<span class="fa fa-upload"></span>
								<span><?php _e('Upload'); ?></span>
								<input type="file" name="files[]" multiple="multiple" />
							</span>
						</div>
					</div>
					
					<div class="file-tags">
						<h3><?php _e('Tags') ?></h3>						
						<ul data-bind="foreach:tags">
							<li>
								<a href="#" class="label" data-bind="text:tag,click:$root.toggleTag,css{'label-default':!selected(),'label-primary':selected()}"></a>
							</li>
						</ul>
						<div class="checkbox">
							<label>
								<input type="checkbox" data-bind="checked:matchAllTags,click:toggleMatchAllTags()" /> <?php _e('Every file must match all tags'); ?>
							</label>
						</div>
						<div data-bind="visible:activeTags().length>0">
							(<a href="#" data-bind="click:clearActiveTags"><?php _e('clear all'); ?></a>)
						</div>
					</div>
					
				</div>
				<div class="col-xs-7 main">
					<div class="row modal-header">
						<h2><?php echo __('Browse files'); ?> <small data-bind="visible:limit()>0">(<?php echo __('max'); ?> <span data-bind="text:limit()"></span>)</small></h2>
					</div>
					<div class="row modal-body">
						
						<div class="col-xs-12">
							<ul class="thumbnails" data-bind="foreach:files">
								<li class="file">
									<a href="#" class="thumbnail filebrowser-thumbnail" data-bind="click:$root.selectFile,css:{toggledthumb:activefile()}">
										<img data-bind="attr{src:medium()}" src="" />
										<div class="progress" data-bind="visible:progress()<100">
											<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" data-bind="html:uploadprogress,style:{width:uploadprogress}">
												
											</div>
										</div>
										<div class="filebrowser-file-checkbox" data-bind="visible:selected()">
											<span class="glyphicon glyphicon-ok"></span>
										</div>
									</a>
								</li>
							</ul>
							
							<div class="loading" data-bind="loader:loading()"></div>
						</div>
						
					</div>
					
				</div>
				
				<div class="col-xs-3 filebrowser-info">
					<div class="clearfix">
						<a href="#" class="close" data-dismiss="modal">x</a>
					</div>
					<div class="row">
						<div>
							<div class="row file-meta" data-bind="if:currentThumbnail()">
								<div class="col-xs-12 col-md-6" id="filebrowser-preview-image">
									<img data-bind="attr{src:currentThumbnail()}" />
								</div>
								<div class="col-xs-12 col-md-6" id="filebrowser-preview-info">
									<div class="filename" data-bind="text:currentfile().filename"></div>
									<div class="filetype" data-bind="text:currentfile().filetype"></div>
									<div class="filedate" data-bind="text:currentfile().filedate"></div>
									<div class="filesize" data-bind="text:currentfile().filesize"></div>
									<div class="filebrowser-delete-permanently text-right danger">
										<span data-bind="visible:currentfile().image">
											<a href="#" data-bind="editfile:currentfile()"><?php _e('Edit') ?></a>
										</span>
										
										<a class="label label-danger" href="#" title="<?php _e('Delete permanently'); ?>" data-bind="click:deleteCurrentFile">
											<span class="glyphicon glyphicon-trash"></span>
										</a>
									</div>
								</div>
							</div>
							
							<div class="filebrowser-forms">
								<div class="form-group">
									<div class="row">
										<div class="col-xs-4 text-right">
											<label for="filebrowser-titletext"><?php echo __('Title:'); ?></label>
										</div>
										<div class="col-xs-8 text-right">
											<input type="text" data-bind="value:(currentfile()?currentfile().title():''),event:{blur:saveFileTitle}" id="filebrowser-titletext" class="form-control" />
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-4 text-right">
											<label for="filebrowser-description"><?php echo __('Description:'); ?></label>
										</div>
										<div class="col-xs-8 text-right">
											<textarea data-bind="value:(currentfile()?currentfile().description():''),event:{blur:saveFileDescription}" id="filebrowser-description" class="form-control"></textarea>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col-xs-4 text-right">
											<label for="filebrowser-alt"><?php echo __('Alt text:'); ?></label>
										</div>
										<div class="col-xs-8 text-right">
											<input type="text" data-bind="value:(currentfile()?currentfile().alt():''),event:{blur:saveFileAlt}" id="filebrowser-alt" class="form-control" />
										</div>
									</div>
								</div>
								<div class="form-group" data-bind="visible:currentfile()?currentfile().image():false">
									<div class="row">
										<div class="col-xs-4 text-right">
											<label for="filebrowser-size"><?php echo __('Size:'); ?></label>
										</div>
										<div class="col-xs-8 text-right">
											<select id="filebrowser-size" class="form-control" data-bind="options:currentfile().versions,optionsValue:'id',optionsText:'title'">
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr />
					<div class="row text-right">
						<p>
							<div class="filecounter" data-bind="visible:selectedfiles().length>0">
								<span id="filecounter" data-bind="text:selectedfiles().length"></span><span data-bind="visible:limit()>0">/<span data-bind="text:limit()"></span></span> <?php _e('file') ?><span id="filecounter-plurality" data-bind="visible:selectedfiles().length!=1"><?php _e('s'); ?></span> <?php _e('selected') ?>
								<span id="filebrowser-clearall">(<a href="#" id="filebrowser-clear" data-bind="click:clearall"><?php _e('clear') ?></a>)</span>
								<a href="#" data-bind="click:deleteSelectedFiles" class="label label-danger">
									<span data-bind="if:selectedfiles().length==1">
										<?php _e('Delete') ?>
									</span>
									<span data-bind="if:selectedfiles().length!=1">
										<?php _e('Delete all') ?>
									</span>
									<span class="glyphicon glyphicon-trash"></span>	
								</a>
							</div>
							<div data-bind="visible:selectedfiles().length==0">
								(<a href="#" data-bind="click:selectAll"><?php _e('Select all'); ?></a>)
							</div>
						</p>
						<p>
							<button class="btn" data-dismiss="modal"><?php _e('Close') ?></button>
							<button class="btn btn-primary insert-files-btn" id="filebrowser-insert-files-btn" data-bind="click:insertFiles,disable:selectedfiles().length<1"><?php _e('Select files') ?></button>
						</p>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
</div>