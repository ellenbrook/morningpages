<?php
		$categories = $content->contenttype->categories->where('parent','=',0)->find_all();
		if((bool)$categories->count())
		{
			$contentcats = $content->categories->find_all();
			$conttencatids = array();
			foreach($contentcats as $cat)
			{
				$conttencatids[] = $cat->id;
			}
			function content_loop_categories($cats, $conttencatids)
			{
				echo '<ul>';
				foreach($cats as $cat)
				{
					echo '<li class="catid-'.$cat->id.'">';
					echo '<label>';
					echo '<input type="checkbox" class="content-category-check" name="category[]" value="'.$cat->id.'" '.(in_array($cat->id, $conttencatids)?' checked="checked"':'').'/> ';
					echo $cat->title;
					echo '</label>';
					$children = ORM::factory('category')->where('parent','=',$cat->id)->find_all();
					if((bool)$children->count())
					{
						content_loop_categories($children, $conttencatids);
					}
					echo '</li>';
				}
				echo '</ul>';
			}
			content_loop_categories($categories, $conttencatids);
		}
?>
