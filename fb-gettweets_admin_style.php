<?php
if (isset($_POST["update_settings"]) && $_POST["update_settings"] === "Y")
{
	$fb_tweet_text_class = esc_attr($_POST["fb_tweet_text_class"]);   
	update_option("fb_tweet_text_class", $fb_tweet_text_class);

	$fb_tweet_time_type = esc_attr($_POST["fb_tweet_time_type"]);   
	update_option("fb_tweet_time_type", $fb_tweet_time_type);

	$fb_tweet_time_ago_class = esc_attr($_POST["fb_tweet_time_ago_class"]);   
	update_option("fb_tweet_time_ago_class", $fb_tweet_time_ago_class);

	$fb_tweet_link_class = esc_attr($_POST["fb_tweet_link_class"]);   
	update_option("fb_tweet_link_class", $fb_tweet_link_class);

	?>
	<div class="updated">
		<p><strong><?php _e('Settings saved.' ); ?></strong></p>
	</div>
	<?php
}
else
{
	$fb_tweet_text_class = get_option("fb_tweet_text_class");
	$fb_tweet_time_ago_class = get_option("fb_tweet_time_ago_class");
	$fb_tweet_link_class = get_option("fb_tweet_link_class");
	$fb_tweet_time_type = get_option("fb_tweet_time_type");

}
?>
<div class="wrap">
	<?php screen_icon('themes'); ?> <h2>Style elements</h2>

	<form method="POST" action="">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="fb_tweet_text_class">
						Tweet Text Class:
					</label> 
				</th>
				<td>
					<input type="text" name="fb_tweet_text_class" value="<?php echo $fb_tweet_text_class;?>" size="25" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="fb_tweet_time_type">
						Time Type:
					</label> 
				</th>
				<td>
					<select name="fb_tweet_time_type">
						<option value="time_ago" <?php echo ($fb_tweet_time_type === 'time_ago' ? 'selected' : '') ?> >
							Time Ago
						</option>
						<option value="created_at" <?php echo ($fb_tweet_time_type === 'created_at' ? 'selected' : '') ?>>
							Created At
						</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="fb_tweet_time_ago_class">
						Time Class:
					</label> 
				</th>
				<td>
					<input type="text" name="fb_tweet_time_ago_class" value="<?php echo $fb_tweet_time_ago_class;?>" size="25" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="fb_tweet_link_class">
						Link Class:
					</label> 
				</th>
				<td>
					<input type="text" name="fb_tweet_link_class" value="<?php echo $fb_tweet_link_class;?>" size="25" />
				</td>
			</tr>
		</table>
		<p>
			<input type="hidden" name="update_settings" value="Y" />
			<input type="submit" value="Save settings" class="button-primary"/>
		</p>
	</form>
</div>