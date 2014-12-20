<?php
	$colors = array(
		'#D1E8D0',
		'#D0D7E8',
		'#E6D0E8',
		'#E8D0D0',
		'#E8E8D0',
		'#E8D1D0',
	);
?>
<div class="widget block" data-blockid="<?php echo $block->id; ?>" data-blocktypeid="<?php echo $block->blocktype_id; ?>" data-type="<?php echo $block->blocktype->type; ?>">
	<div class="widget-header">
		<div class="pull-left widget-tool widget-tool-left widget-mover<?php echo ($block->parent==0?' root':''); ?>">
			<span class="glyphicon glyphicon-move"></i>
		</div>
		<div class="pull-left widget-tool widget-tool-left">
			<a href="#" data-bind="click:toggleOpen">
				<span class="glyphicon" data-bind="css:{'glyphicon-chevron-down':collapsed()==0,'glyphicon-chevron-right':collapsed()==1}"></span>
			</a>
		</div>
		<h3 class="widget-title pull-left">
			<span></span><?php echo $block->blocktype->display; ?></span>
			<small data-bind="text:excerpt"></small>
		</h3>
		<?php //if($block->blocktype->min != 1): ?>
			<div class="widget-tool pull-right">
				<a data-bind="click:removeme" href="#" class="block-deleter" title="Slet denne blok">
					<span class="glyphicon glyphicon-trash"></span>
				</a>
			</div>
		<?php //endif; ?>
	</div>
	<div <?php /*style="background:<?php echo $colors[rand(0,count($colors)-1)] ?>;"*/?> class="widget-body" data-bind="css{hide:collapsed()=='1'}">
<?php
		$blockview = view::factory('Cms/Content/blocks/'.$block->blocktype->type);
		$blockview->block = $block;
		echo $blockview->render();
?>
	</div>
</div>