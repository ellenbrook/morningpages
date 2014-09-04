<form action="" method="post" accept-charset="<?php echo Kohana::$charset ?>">
<p>Your dirty HTML goes here:<br/>
<?php echo Form::textarea('dirty', $dirty, array('style' => 'width:98%')) ?>
</p>
<button type="submit">Clean</button>
</form>

<?php if (isset($clean)): ?>
<p>This is your cleaned HTML:<br/></p>
<pre class="code:html"><?php echo HTML::chars($clean) ?></pre>
<?php endif ?>

