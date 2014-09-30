<section class="container">
	<h2>Me/<?php echo user::get()->username; ?></h2>
</section>

<section class="container">
<?php
	
	/*$userachievements = user::get()->userachievements->find_all();
	if((bool)$userachievements->count())
	{
		foreach($userachievements as $userachievement)
		{
			echo HTML::image('media/img/badges/'.$userachievement->achievement->badge);
		}
	}*/
?>
</section>
