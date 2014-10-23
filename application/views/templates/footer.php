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
				<li><a href="https://www.facebook.com/morningpages" target="_blank">Facebook</a></li>
				<li><a href="https://twitter.com/morningpagesnet">Twitter</a></li>
			</ul>
			<ul>
				<li><h3>Legal</h3></li>
				<li><a href="<?php echo URL::site('terms-conditions'); ?>">Terms and Conditions</a></li>
				<li><a href="<?php echo URL::site('privacy-policy'); ?>">Privacy Policy</a></li>
			</ul>
		</div>
		<hr>
		<p>We take privacy very seriously. If you have specific questions, please don't hesitate to ask about them on <a href="<?php echo URL::site('talk'); ?>" title="Discuss Morning Pages">our forum</a>.</p>
	</div>
</footer>
<?php

if(!user::logged())
{
    echo View::factory('modals/login');
    echo View::factory('modals/register');
}

echo View::factory('modals/tipsandtricks');

?>