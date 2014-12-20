<option value="0">Ingen</option>
<?php
function loop_categories_for_select($cats, $level = 0)
{
	if((bool)$cats->count())
	{
		foreach($cats as $cat)
		{
			echo '<option value="'.$cat->id.'">';
			echo str_repeat('&mdash;', $level);
			echo $cat->title;
			echo '</option>';
			$kids = ORM::factory('category')->where('parent','=',$cat->id)->find_all();
			if((bool)$kids->count())
			{
				loop_categories_for_select($kids, ($level+1));
			}
		}
	}
}
$cats = ORM::factory('category')->where('contenttype_id','=',$content->contenttype)->where('parent','=',0)->find_all();
if((bool)$cats->count())
{
	loop_categories_for_select($cats);
}