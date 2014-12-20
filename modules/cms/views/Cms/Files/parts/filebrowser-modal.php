<?php
	$allfiles = ORM::factory('File')->find_all();
?>
<div class="modal filebrowsermodal" id="filebrowsermodal" data-totalfiles="<?php echo $allfiles->count(); ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a href="#" class="close" data-dismiss="modal">x</a>
				<h2>Gennemse filer</h2>
			</div>
			<div class="modal-body">
				<div class="row-fluid">
					<div class="span9 images">
						<div class="well filebrowserdropzone">
							<div class="row">
								<div class="file-uploader col-xs-6" data-limit="1">
									<span class="btn btn-default fileinput-button">
										<span class="glyphicon glyphicon-upload"></span>
										<span>Upload</span>
										<input type="file" name="files[]" multiple="multiple" />
									</span>
								</div>
								<div class="col-xs-6 message">Træk og slip filer her for at uploade</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<ul class="thumbnails">
							<li class="placeholder-template hide">
								<a href="#" class="thumbnail filebrowser-thumbnail placeholder">
									<img src="<?php echo url::site('/media/img/filebrowser-thumbnail-placeholder.png'); ?>" />
									<div class="filebrowserprogress"><div class="filebrowserbar" style="width: 0%;"></div></div>
								</a>
							</li>
						</ul>
						<div class="loading">
							<img src="<?php echo URL::site('media/img/ajax-loader.gif'); ?>" alt="Henter billeder..." /> Henter billeder...
						</div>
					</div>
					<div class="span3 filebrowser-info">
						<div class="status text-right">
							<div id="filebrowserstatus"></div>
						</div>
						<div class="row-fluid">
							<div class="span6" id="filebrowser-preview-image">
								
							</div>
							<div class="span6" id="filebrowser-preview-info">
								<div class="filename"></div>
								<div class="filetype"></div>
								<div class="filedate"></div>
								<div class="filesize"></div>
							</div>
						</div>
						<div class="filebrowser-forms hide">
							<div class="control-group">
								<label for="filebrowser-titletext">Titel</label>
								<input type="text" id="filebrowser-titletext" />
							</div>
							<div class="control-group">
								<label for="filebrowser-description">Beskrivelse</label>
								<textarea id="filebrowser-description"></textarea>
							</div>
							<div class="control-group">
								<label for="filebrowser-size">Størrelse</label>
								<select id="filebrowser-size">
									<option value="small">Lille (100x100)</option>
									<option value="medium">Medium (300x300)</option>
									<option value="large">Stor (600x600)</option>
									<option value="original" id="filebrowser-originalsize">Original</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="row-fluid">
					<div class="span6">
						<div class="filecounter"><span id="filecounter">0</span> fil<span id="filecounter-plurality">er</span> valgt <span id="filebrowser-clearall" class="hide">(<a href="#" id="filebrowser-clear">ryd</a>)</span></div>
					</div>
					<div class="span6 text-right">
						<button class="btn" data-dismiss="modal">Luk</button>
						<button class="btn btn-primary insert-files-btn">Vælg filer</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>