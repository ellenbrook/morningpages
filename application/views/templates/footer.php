<footer class="footer">
	<div class="container">
		<div class="footer-links">
			<ul>
				<li><h3>Navigation</h3></li>
				<li><a href="<?php echo URL::site('about'); ?>" title="About Morning Pages">About</a></li>
				<li><a href="<?php echo URL::site('write'); ?>" title="Write">Write</a></li>
				<li><a href="<?php echo URL::site('talk'); ?>" title="Discuss Morning Pages">Talk</a></li>
				<li><a href="<?php echo URL::site('contact'); ?>" title="Contact Morning Pages">Contact</a></li>
			</ul>
			<ul>
				<li><h3>Follow Us</h3></li>
				<li><a href="https://www.facebook.com/morningpages" target="_blank" title="Follow Morning Pages on Facebook">Facebook</a></li>
				<li><a href="https://twitter.com/morningpagesnet" target="_blank" title="Follow Morning Pages on Twitter">Twitter</a></li>
				<li><a href="https://github.com/ellenbrook/morningpages" title="Follow Morning Pages on our Github repository" target="_blank">Github</a></li>
			</ul>
			<ul>
				<li><h3>Legal</h3></li>
				<li><a href="<?php echo URL::site('terms-conditions'); ?>">Terms and Conditions</a></li>
				<li><a href="<?php echo URL::site('privacy-policy'); ?>">Privacy Policy</a></li>
			</ul>
		</div>
		<hr>
<!-- 		<p>We take privacy very seriously. If you have specific questions, please don't hesitate to ask about them on <a href="<?php echo URL::site('talk'); ?>" title="Discuss Morning Pages">our forum</a>.</p> -->
	</div>
	<section class="footer-stats">
<?php
		$words = DB::query(Database::SELECT, "SELECT SUM(wordcount) as words FROM `pages` WHERE `type` = 'page'")->execute()->as_array();
		$words = array_pop($words);
?>
		<p>
			Morning Pages boasts <span class="stat"><?php echo ORM::factory('User')->count_all() ?></span> dedicated users whom have written a combined <span class="stat"><?php echo number_format(arr::get($words, 'words'),0); ?></span> words over <span class="stat"><?php echo number_format(ORM::factory('Page')->count_all(),0); ?></span> pages.
		</p>
		</section>
</footer>

<div id="fb-root"></div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47689215-1', 'auto');
  ga('send', 'pageview');

</script>
<?php

if(!user::logged())
{
    
    
}
echo View::factory('modals/login');
echo View::factory('modals/register');
echo View::factory('modals/tipsandtricks');

?>
