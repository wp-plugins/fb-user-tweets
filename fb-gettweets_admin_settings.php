<?php

if($_POST['fb_gettweets_settings_hidden'] == 'Y') {
        //Form data sent

	$fb_gettweets_post_type = esc_attr($_POST['fb_gettweets_post_type']);
	update_option('fb_gettweets_post_type', json_encode($fb_gettweets_post_type));

	$twitter_user_name = esc_attr($_POST['twitter_user_name']);
	update_option('pt_twitter_user_name', $twitter_user_name);

	$twitter_tweet_count = esc_attr($_POST['twitter_tweet_count']);
	if ($twitter_tweet_count > 199)
		$twitter_tweet_count = 100;
	update_option('pt_twitter_tweet_count', $twitter_tweet_count);

	// To avoid hitting Twitter API limit, refresh every 2 minutes if set to 30 sec;
	$twitter_refresh_rate = esc_attr($_POST['twitter_refresh_rate']);
	if ($twitter_refresh_rate < 30)
		$twitter_refresh_rate = 120;
	update_option('pt_twitter_refresh_rate', $twitter_refresh_rate);

	$twitter_consumer_key = esc_attr($_POST['twitter_consumer_key']);
	update_option('pt_twitter_consumer_key', $twitter_consumer_key);

	$twitter_consumer_secret = esc_attr($_POST['twitter_consumer_secret']);
	update_option('pt_twitter_consumer_secret', $twitter_consumer_secret);

	$twitter_access_token = esc_attr($_POST['twitter_access_token']);
	update_option('pt_twitter_access_token', $twitter_access_token);

	$twitter_access_token_secret = esc_attr($_POST['twitter_access_token_secret']);
	update_option('pt_twitter_access_token_secret', $twitter_access_token_secret);

	$twitter_connected = fb_getweets_verify_tokens();
	update_option('pt_twitter_connected', $twitter_connected);

	// Refresh twitter feed
	updated_twitter_cache();

	?>
	<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
	<?php
} else {
       //Normal page display
	$twitter_user_name = get_option('pt_twitter_user_name');
	$twitter_tweet_count = get_option('pt_twitter_tweet_count');
	$twitter_refresh_rate = get_option('pt_twitter_refresh_rate');
	$twitter_consumer_key = get_option('pt_twitter_consumer_key');
	$twitter_consumer_secret = get_option('pt_twitter_consumer_secret');
	$twitter_access_token = get_option('pt_twitter_access_token');
	$twitter_access_token_secret = get_option('pt_twitter_access_token_secret');
	$twitter_connected = get_option('pt_twitter_connected');
}
?>
<div class="wrap">
	<form name="fb_gettweets_form" method="POST" action="">
		<?php    echo "<h4>" . __( 'Twitter Settings', 'fb_gettweets_trdom' ) . "</h4>"; ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="">
						<?php _e("Connected: " ); ?>
					</label> 
				</th>
				<td>
					<?php echo $twitter_connected; ?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_user_name">
						<?php _e("Twitter User: " ); ?>
					</label> 
				</th>
				<td>
					<input type="text" name="twitter_user_name" value="<?php echo $twitter_user_name; ?>" size="60">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_tweet_count">
						<?php _e("Tweet Count: " ); ?>
					</label> 
				</th>
				<td>
					<input type="text" name="twitter_tweet_count" value="<?php echo $twitter_tweet_count; ?>" size="60">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_refresh_rate">
						<?php _e("Refresh every (in seconds): " ); ?>
					</label> 
				</th>
				<td>
					<input type="text" name="twitter_refresh_rate" value="<?php echo $twitter_refresh_rate; ?>" size="60">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_consumer_key">
						<?php _e("[APP]Consumer Key: " ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="twitter_consumer_key" value="<?php echo $twitter_consumer_key; ?>" size="60">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_consumer_secret">
						<?php _e("[APP]Consumer Secret: " ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="twitter_consumer_secret" value="<?php echo $twitter_consumer_secret; ?>" size="60">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_access_token">
						<?php _e("[USER]Access Token: " ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="twitter_access_token" value="<?php echo $twitter_access_token; ?>" size="60">
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label for="twitter_access_token_secret">
						<?php _e("[USER]Token Secret: " ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="twitter_access_token_secret" value="<?php echo $twitter_access_token_secret; ?>" size="60">
				</td>
			</tr>
		</table>
		<p>
			<input type="hidden" name="fb_gettweets_settings_hidden" value="Y">
			<input type="submit" value='<?php _e('Save Settings'); ?>' class="button-primary"/>
		</p>
	</form>
</div>