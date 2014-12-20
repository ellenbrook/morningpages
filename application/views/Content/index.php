<div id="home">
<?php

$blocks = $page
	->blocks
	->where('parent','=',0)
	->find_all();

if((bool)$blocks->count())
{
	foreach($blocks as $block)
	{
		if($block->blocktype->key != 'header')
		{
			echo '<section>';
		}
		switch($block->blocktype->key)
		{
			case 'content':
			case 'text':
				echo '<div class="largetext">';
				echo $block->value;
				echo '</div>';
				break;
				
			case 'headline':
				echo '<h2>'.$block->value.'</h2>';
				break;
			case 'news':
				$headline = $block->get_block_by_key('news.headline');
				$headline_id = $headline->get_block_by_key('news.headline.id');
				$headline_text = $headline->get_block_by_key('news.headline.text');
				$numposts = $block->get_block_by_key('news.numposts');
				echo '<h2 id="'.$headline_id.'">'.$headline_text.'</h2>';
				$posts = ORM::factory('Content')
					->where('contenttypetype_id','=',7)
					->limit($numposts->value)
					->order_by('created','DESC')
					->find_all();
				if((bool)$posts->count())
				{
					echo '<div class="row">';
					foreach($posts as $post)
					{
?>
						<div class="col-xs-6 col-md-3">
							<div class="article">
								<div class="article-thumb">
									<?php if($post->has_thumb()): ?>
										<?php echo $post->link(array('content'=>'<img src="'.$post->get_thumb(350, 200).'" class="img-responsive" />')) ?>
									<?php endif; ?>
								</div>
								<h3 class="article-title"><?php echo $post->link(); ?></h3>
								<div class="article-excerpt">
<?php
									$excerpt = $post->get_block_by_key('excerpt');
									if($excerpt)
									{
										echo $excerpt;
									}
?>
								</div>
							</div>
	</div>
<?php
					}
					echo '</div>';
				}
				break;
				
			case 'clients':
				$headline = $block->get_block_by_key('clients.headline');
				$headline_id = $headline->get_block_by_key('clients.headline.id');
				$headline_text = $headline->get_block_by_key('clients.headline.text');
				$clients = $block->get_blocks('clients.client');
				echo '<h2 id="'.$headline_id.'">'.$headline_text.'</h2>';
				$content = $block->get_block_by_key('clients.content');
				echo '<div class="largetext">';
				echo $content;
				echo '</div>';
				if((bool)$clients->count())
				{
					echo '<div class="row">';
					foreach($clients as $client)
					{
						$id = json_decode($client->value);
						//var_dump($ids);
						//$id = arr::get($ids, 0, 0);
						$client = ORM::factory('Content', $id);
						echo '<div class="col-xs-6 col-md-3">';
						echo '<div class="client">';
						if($client->has_thumb())
						{
							echo $client->link(array('content'=>'<img src="'.$client->get_thumb(250, 150).'" class="img-responsive" />'));
						}
						echo '</div>';
						echo '</div>';
					}
					echo '</div>';
				}
				break;
				
			case 'services':
				$headline = $block->get_block_by_key('services.headline');
				$headline_id = $headline->get_block_by_key('services.headline.id');
				$headline_text = $headline->get_block_by_key('services.headline.text');
				$content = $block->get_block_by_key('services.content');
				echo '<h2 id="'.$headline_id.'">'.$headline_text.'</h2>';
				echo '<div class="largetext">';
				echo $content;
				echo '</div>';
				$sections = $block->get_blocks_by_key('services.section');
				if((bool)$sections->count())
				{
					echo '<script type="text/javascript">';
					echo 'servicesdata = []';
					echo '</script>';
					$i=1;
					foreach($sections as $section)
					{
						$headline = $section->get_block_by_key('services.section.headline');
						$description = $section->get_block_by_key('services.section.description');
						$graph = $section->get_block_by_key('services.section.graph');
						
						$points = $graph->get_blocks_by_key('services.section.graph.point');
						
						echo '<script type="text/javascript">';
						echo 'servicesdata["'.$headline.'"] = []';
						echo '</script>';
						
						$data = array();
						if((bool)$points->count())
						{
							echo '<script type="text/javascript">';
							foreach($points as $point)
							{
								$service = $point->get_block('services.section.graph.point.service');
								$service = json_decode($service);
								$service = arr::get($service,0,false);
								$service = ORM::factory('Content', $service);
								
								$percent = $point->get_block('services.section.graph.point.percent');
								
								$data[$service->title] = $percent;
								
								echo 'servicesdata["'.$headline.'"].push(["'.$service->title.'",'.$percent.']);';
							}
							echo '</script>';
						}
						$js = '[';
						$j=0;
						foreach($data as $title => $percent)
						{
							if($j>0)
							{
								$js.=',';
							}
							$js.="['".$title."',".$percent.']';
							$j++;
						}
						$js.=']';
						
						
						echo '<div class="row services-waypoint">';
						if($i%2==0)
						{
							echo '<div class="col-xs-6 chart" data-name="'.$headline.'">';
							
							echo '</div>';
							echo '<div class="col-xs-6">';
							echo '<h3>'.$headline.'</h3>';
							echo $description;
							echo '</div>';
						}
						else
						{
							echo '<div class="col-xs-6">';
							echo '<h3>'.$headline.'</h3>';
							echo $description;
							echo '</div>';
							echo '<div class="col-xs-6 chart" data-name="'.$headline.'">';
							
							echo '</div>';
						}
						echo '</div>';
						$i++;
					}
				}
				break;
			case 'contact':
				$headline = $block->get_block_by_key('contact.headline');
				$headline_id = $headline->get_block_by_key('contact.headline.id');
				$headline_text = $headline->get_block_by_key('contact.headline.text');
				echo '<h2 id="'.$headline_id.'">'.$headline_text.'</h2>';
				echo '<div class="row">';
				echo '<div class="col-xs-12 col-md-6">';
				echo $block->get_block_by_key('contact.text');
				echo '</div>';
				echo '<div class="col-xs-12 col-md-6">';
				echo '<form method="post" id="home-contact-form">';
				$form = $block->get_block_by_key('contact.form');
				$elements = $form->get_block_by_key('contact.form.elements');
				$subs = $elements->blocks->find_all();
				if((bool)$subs->count())
				{
					foreach($subs as $sub)
					{
						echo '<div class="form-group">';
						$mandatory = $sub->get_block_by_key($sub->blocktype->key.'.mandatory');
						$mandatory = ($mandatory == 'Ja');
						echo '<label for="home-contact-element-'.$sub->id.'">'.$sub->get_block_by_key($sub->blocktype->key.'.name').($mandatory?' *':'').'</label>';
						switch($sub->blocktype->key)
						{
							case 'contact.form.elements.textfield':
								echo '<input class="form-control" type="text" name="'.$sub->get_block_by_key('contact.form.elements.textfield.name').'" placeholder="'.$sub->get_block_by_key('contact.form.elements.textfield.placeholder').'" />';
								break;
							case 'contact.form.elements.email':
								echo '<input class="form-control" type="email" name="'.$sub->get_block_by_key('contact.form.elements.email.name').'" placeholder="'.$sub->get_block_by_key('contact.form.elements.email.placeholder').'" />';
								break;
							case 'contact.form.elements.textarea':
								echo '<textarea class="form-control" name="'.$sub->get_block_by_key('contact.form.elements.textarea.name').'" placeholder="'.$sub->get_block_by_key('contact.form.elements.textarea.placeholder').'"></textarea>';
								break;
						}
						echo '</div>';
					}
				}
				echo '<div><button class="btn btn-primary">Send besked</button>';
				echo '</form>';
				echo '</div>';
				echo '</div>';
				break;
			
		}
		/*switch($block->blocktype->type)
		{
			case 'richtext':
				echo $block->value;
				break;
			case 'complex':
				loop_blocks($block->blocks->find_all());
				break;
		}*/
		echo '</section>';
	}
}
?>
</div>
