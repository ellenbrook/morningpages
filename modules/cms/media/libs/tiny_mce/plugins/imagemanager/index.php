<?php
include('functions.php');

?>
<!DOCTYPE html>
<html>
<head>
	<title>TinyMCE Image Manager</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="../../tiny_mce.js"></script>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
</head>
<body>

<div clqss="container-fluid">
	
	<div class="row-fluid">
		<div class="span12">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#filetab-browse">Gennemse</a>
				</li>
				<?php /*<li>
					<a href="#filetab-external">Indsæt fra URL</a>
				</li>*/ ?>
			</ul>
			
			<div class="tab-content">
				<div class="tab-pane active" id="filetab-browse">
					<div class="pull-right">
						<input type="text" id="searcher" placeholder="Søg i filerne" />
					</div>
<?php
					$limit = 20;
					$files = ORM::factory('file')->limit($limit)->find_all();
					$total = ORM::factory('file')->count_all();
					$numpages = ceil($total/$limit);
					if((bool)$files->count())
					{
						if($numpages > 1)
						{
							echo '<div class="pagination pagination-small">';
							echo '<ul>';
							for($i=1;$i<=$numpages;$i++)
							{
								echo '<li class="'.($i==1?'active':'').'"><a href="#" data-page="'.$i.'" class="pagination-toggler" title="Side '.$i.'">'.$i.'</a></li>';
							}
							echo '</ul>';
							echo '</div>';
						}
?>
						<div class="clearfix"></div>
						<img src="img/ajax-loader.gif" alt="Henter" class="hide" id="loadindicator" />
						<table class="table table-striped" id="imagestable">
							<thead>
								<tr>
									<th></th>
									<th>Filnavn</th>
									<th>Størrelse</th>
								</tr>
							</thead>
							<tbody id="imagestablebody">
<?php
						foreach($files as $file)
						{
							echo filehelper::tableoutput($file);
						}
						echo '</tbody></table>';
					}
					if($numpages > 1)
					{
						echo '<div class="pagination pagination-small">';
						echo '<ul>';
						for($i=1;$i<=$numpages;$i++)
						{
							echo '<li class="'.($i==1?'active':'').'"><a href="#" data-page="'.$i.'" class="pagination-toggler" title="Side '.$i.'">'.$i.'</a></li>';
						}
						echo '</ul>';
						echo '</div>';
					}
?>
				</div>
				
				<?php /*<div class="tab-pane" id="filetab-external">
					<div class="row-fluid">
						<div class="previewholder pull-left">
							<div class="url-preview-image-container">
								<img src="img/ajax-loader.gif" class="img-polaroid" id="url-preview-image" />
							</div>
							<img src="img/ajax-loader.gif" class="img-polaroid hide" id="url-preview-image" />
						</div>
						<div class="imageinfo">
							<div class="control-group">
								<label for="insert-url">URL</label>
								<input type="text" class="input-large" id="insert-url" />
							</div>
						</div>
					</div>
				</div> */ ?>
				
			</div>
		</div>
	</div>
	
</div>

<div class="modal hide fade" id="imgmodal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="imgmodal-headline">Indsæt billede</h3>
	</div>
	<div class="modal-body" id="imgmodal-body">
		<div class="previewholder pull-left">
			<img src="img/ajax-loader.gif" class="img-polaroid" id="preview-image" />
			<div class="control-group">
				<label for="image-alignment">Justering</label>
				<select id="image-alignment">
					<option selected="selected" value="none">Ingen</option>
					<option value="left">Venstre</option>
					<option value="right">Højre</option>
				</select>
			</div>
			<div id="alignment-preview">
				<img src="img/sample.gif" id="alignment-preview-image" />
				Cupcake ipsum dolor sit amet lollipop sweet roll. Jelly-o pastry halvah.
			</div>
		</div>
		<div class="imageinfo">
			<div class="control-group">
				<label for="image-alttext">Titel / Alttekst</label>
				<input type="text" id="image-alttext" value="" />
			</div>
			<div class="control-group">
				<label for="image-description">Beskrivelse</label>
				<textarea id="image-description"></textarea>
			</div>
			<div class="control-group">
				<label for="image-width">Dimensioner</label>
				<div class="form-inline">
					<input type="text" class="input-mini" id="image-width" title="Bredde" />
					x
					<input type="text" class="input-mini" id="image-height" title="Højde" />
				</div>
			</div>
			<div class="control-group">
				<label for="proportional-scale" class="checkbox">
					<input type="checkbox" id="proportional-scale" value="yes" checked="checked" />
					Bevar proportionerne
				</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Fortryd</button>
		<button class="btn btn-primary" id="insert-img-btn">Indsæt billede</button>
	</div>
</div>

<div class="modal hide fade" id="filemodal">
	<div class="modal-header">
		<h3>Indsæt fil</h3>
	</div>
	<div class="modal-body">
		<div class="previewholder pull-left">
			<img src="img/ajax-loader.gif" class="img-polaroid" id="preview-image" />
		</div>
		<div class="imageinfo">
			info
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal">Fortryd</button>
		<button class="btn btn-primary" id="insert-file-btn">Indsæt fil</button>
	</div>
</div>

</body>
</html>