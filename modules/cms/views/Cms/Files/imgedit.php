<?php
	echo cms::breadcrumbs(array(
		'Filer' => cms::url('files'),
		'Rediger fil' => cms::url('files/edit/'.$file->id),
		'Billedredigering' => ''
	));
?>
<div class="page-header">
	<h1>Rediger billede</h1>
</div>

<div class="row-fluid">
	<div class="span8">
		<p>
			Træk på billedet for at beskære
		</p>
		<input type="hidden" data-orgimg="<?php echo url::site('files/'.$file->filename()); ?>" id="imgdata" value="" />
		<div id="image" data-fileid="<?php echo $file->id; ?>">
			<img id="theimg" data-angle="0" src="<?php echo url::site('files/'.$file->filename()); ?>" />
		</div>
		<div class="text-right">
			<a href="#" class="btn hide btn-primary" id="crop-btn">Beskær</a>
		</div>
	</div>
	<div class="span4" id="tools">
		<h3>Skaler</h3>
		<div class="imgproportions form-inline">
<?php
			list($width,$height) = getimagesize('files/'.$file->filename());
?>
			<input type="text" id="image-width" class="input-mini" data-orgwidth="<?php echo $width; ?>" data-scaledwidth="500" value="<?php echo $width; ?>" />
			<label for="image-height">x</label>
			<input type="text" id="image-height" class="input-mini" data-orgheight="<?php echo $height; ?>" data-scaledheight="313" value="<?php echo $height; ?>" />
			<button class="btn btn-small btn-primary" id="scalingbtn">Skaler</button>
		</div>
		
		<div class="clearfix">
			<?php if(function_exists('imagerotate')): ?>
				<h3>Roter</h3>
				<a href="#" title="Roter mod uret" class="rotate-counterclockwise"></a>
				<a href="#" title="Roter med uret" class="rotate-clockwise"></a>
				<?php /*<a href="#" title="Flip horizontalt" class="flip-horizontal"></a>
				<a href="#" title="Flip vertikalt" class="flip-vertical"></a> */ ?>
			<?php endif; ?>
		</div>
		
		<div id="functions">
			<?php if(defined('IMG_FILTER_BRIGHTNESS')): ?>
				
				<h3>Funktioner</h3>
				
				<div class="btn-group">
					<a href="#" class="btn" id="grayscale">Sort/Hvid</a>
					<a href="#" class="btn" id="negate">Negativ</a>
					<a href="#" class="btn" id="emboss">Emboss</a>
					<a href="#" class="btn" id="edgedetect">Edge detect</a>
				</div>
				
				<h3>Juster</h3>
				
				<div class="row-fluid">
					<div class="span4">
						<h4>Blur</h4>
						<div class="btn-group">
							<a href="#" class="btn" id="blur">
								<i class="icon-minus"></i>
							</a>
							<a href="#" class="btn more" id="blur-more">
								<i class="icon-plus"></i>
							</a>
						</div>
					</div>
					<div class="span4">
						<h4>Lys</h4>
						<div class="btn-group">
							<a href="#" class="btn less" id="brightness-less">
								<i class="icon-minus"></i>
							</a>
							<a href="#" class="btn" id="brightness-more">
								<i class="icon-plus"></i>
							</a>
						</div>
					</div>
					<div class="span4">
						<h4>Kontrast</h4>
						<div class="btn-group">
							<a href="#" class="btn less" id="contrast-less">
								<i class="icon-minus"></i>
							</a>
							<a href="#" class="btn" id="contrast-more">
								<i class="icon-plus"></i>
							</a>
						</div>
					</div>
				</div>
				
				<div class="row-fluid">
					<div class="span4">
						<h4>Pixelering</h4>
						<div class="btn-group">
							<a href="#" class="btn less" id="pixelate-less">
								<i class="icon-minus"></i>
							</a>
							<a href="#" class="btn" id="pixelate-more">
								<i class="icon-plus"></i>
							</a>
						</div>
					</div>
					<div class="span4">
						<h4>Smoothing</h4>
						<div class="btn-group">
							<a href="#" class="btn less" id="smooth-less">
								<i class="icon-minus"></i>
							</a>
							<a href="#" class="btn" id="smooth-more">
								<i class="icon-plus"></i>
							</a>
						</div>
					</div>
					<div class="span4">
						
					</div>
				</div>
				
			<?php endif; ?>
			
			<?php /*<a href="#" class="btn" id="colorize-more">Mere colorize</a>
			<a href="#" class="btn less" id="colorize-less">Mindre colorize</a> */ ?>
		</div>
		
		<hr />
		
		<div>
			<button class="btn disabled" id="undo-btn">Fortryd</button>
			<button class="btn btn-primary disabled" id="save-btn">Gem</button>
		</div>
		
	</div>
	
</div>
