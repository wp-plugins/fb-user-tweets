<?php
/*
Plugin Name: FB User Tweets
Plugin URI: http://www.flickerbox.com
Description: Get Twitter feed
Author: Hamza Essaoui
Version: 1.0
*/
?>
<?php

function fb_getweets_admin_settings() {
	include(__DIR__.'/fb-gettweets_admin_settings.php');
}

function fb_getweets_admin_table() {
	include(__DIR__.'/fb-gettweets_admin_table.php');
}

function fb_getweets_admin_style() {
	include(__DIR__.'/fb-gettweets_admin_style.php');
}

function fb_getweets_admin_actions() {
	add_menu_page("FB User Tweets", "FB Tweets Settings", 'manage_options', "fb_gettweets_settings", "fb_getweets_admin_settings");

    add_submenu_page( 'fb_gettweets_settings', 'FB Tweets Table', 'FB Tweets View', 'manage_options', 'fb_getweets_admin_table', 'fb_getweets_admin_table' );

	add_submenu_page( 'fb_gettweets_settings', 'FB Tweets Style', 'FB Tweets Style', 'manage_options', 'fb_getweets_admin_style', 'fb_getweets_admin_style' );
}

add_action('admin_menu', 'fb_getweets_admin_actions');

function fb_getweets_init_twitter()
{
	// Twitter Class
	require_once('twitteroauth.php');

	$twitter_consumer_key = get_option('pt_twitter_consumer_key');
	$twitter_consumer_secret = get_option('pt_twitter_consumer_secret');
	$twitter_access_token = get_option('pt_twitter_access_token');
	$twitter_access_token_secret = get_option('pt_twitter_access_token_secret');

	// Connect to Twitter
	$connection = new TwitterOAuth($twitter_consumer_key, $twitter_consumer_secret, $twitter_access_token, $twitter_access_token_secret);
	return $connection;
}
function fb_getweets_get_tweets()
{
	$tweets_count = get_option('pt_twitter_tweet_count');
	$twitter_user_name = get_option('pt_twitter_user_name');

	$connection = fb_getweets_init_twitter();
	if (!$connection)
	{
		return false;
	}
	$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$twitter_user_name."&count=". $tweets_count ."&include_entities=true");

	return $tweets;
}

// Get tweets to verify tokens
function fb_getweets_verify_tokens()
{
	// This is not good
	// TODO update return message and use twitter count data instead of first element
	$tweets = fb_getweets_get_tweets();
	if ($tweets !== false && !isset($tweets->errors) && is_array($tweets) && $tweets[0]->created_at)
		return "Yes";
	else
		return "No, Check settings";
}

/**
 * Add html to tweets based on style
 * @param  integer
 * @param  integer
 * @return string
 */
function format_tweet($nbr_of_tweets = false, $tweets_offset = 0)
{
	$formatted_tweets = '';
	$tweets_data = fb_get_tweets($nbr_of_tweets, $tweets_offset);

	if (is_array($tweets_data))
	{
		$twitter_user_name = get_option('pt_twitter_user_name');
		$fb_tweet_text_class = get_option("fb_tweet_text_class");
		$fb_tweet_time_ago_class = get_option("fb_tweet_time_ago_class");
		$fb_tweet_time_type = get_option("fb_tweet_time_type");
		$fb_tweet_link_class = get_option("fb_tweet_link_class");

		$formatted_tweets .= '<div class="twitter-wrapper">';
		foreach ($tweets_data as $key => $tweet) {

			$formatted_tweets .= '<article class="single-tweet">';
			$formatted_tweets .= '<div class=" match-height">';
			$formatted_tweets .= '<div class="copy-wrapper">';
			$formatted_tweets .= '<p class="'.$fb_tweet_time_ago_class.'">'.($fb_tweet_time_type === 'time_ago' ? twitter_time($tweet->created_at) : $tweet->created_at).'</p>';
			$formatted_tweets .= '<p class="'.$fb_tweet_text_class .'">'.$tweet->text.'</p>';
			$formatted_tweets .= '</div>';
			$formatted_tweets .= '</div>';
			$formatted_tweets .= '</article>';
		}
		$formatted_tweets .= '<h3>Follow <span class="fb_tweet_link_class"><a href="//twitter.com/'.$twitter_user_name.'">@'.$twitter_user_name.'</a></span></h3>';
		$formatted_tweets .= '</div>';

		return linkify_filtered($formatted_tweets);
	}
}

function twitter_time($created_at) {
    //get current timestampt
	$b = strtotime("now");
    //get timestamp when tweet created
	$c = strtotime($created_at);
    //get difference
	$d = $b - $c;
    //calculate different time values
	$minute = 60;
	$hour = $minute * 60;
	$day = $hour * 24;
	$week = $day * 7;

	if(is_numeric($d) && $d > 0) {
        //if less then 3 seconds
		if($d < 3) return "right now";
        //if less then minute
		if($d < $minute) return floor($d) . " seconds ago";
        //if less then 2 minutes
		if($d < $minute * 2) return "about 1 minute ago";
        //if less then hour
		if($d < $hour) return floor($d / $minute) . " minutes ago";
        //if less then 2 hours
		if($d < $hour * 2) return "about 1 hour ago";
        //if less then day
		if($d < $day) return floor($d / $hour) . " hours ago";
        //if more then day, but less then 2 days
		if($d > $day && $d < $day * 2) return "yesterday";
        //if less then year
		if($d < $day * 365) return floor($d / $day) . " days ago";
        //else return more than a year
		return "over a year ago";
	}
}

/**
 * Change twitter text into links
 * @param  string
 * @param  string
 * @return string
 */
function linkify_filtered($text, $fb_tweet_link_class = 'fb_tweet_link_class') {
	$text = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', "<span class='".$fb_tweet_link_class."'><a href='$1' target='_blank'>$1</a></span>", $text);
	$text = preg_replace("/@(\w+)/", "<span class='".$fb_tweet_link_class."'><a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a></span>", $text);
	$text = preg_replace("/#(\w+)/", "<span class='".$fb_tweet_link_class."'><a href=\"https://twitter.com/hashtag/\\1\" target=\"_blank\">#\\1</a></span>", $text);
	return $text;
}

/**
 * Load tweets from database
 * @param  boolean
 * @param  integer
 * @return [array]
 */
function fb_get_tweets($nbr_of_tweets = false, $tweets_offset = 0)
{
	$tweets_res = array();
	$tweet_count = get_option('pt_twitter_tweet_count');

	if ($nbr_of_tweets == false || $nbr_of_tweets < 0 || $nbr_of_tweets > $tweet_count || ($nbr_of_tweets + $tweets_offset) > $tweet_count)
		$nbr_of_tweets = $tweet_count;
	if ($tweets_offset < 0 || $tweets_offset >= $tweet_count)
		$tweets_offset = 0;

	if ($tweet_count > 0)
	{
		if ( false === ( $test_tweet_time = get_transient( 'fb-get-tweets-0' ) ) ) {
			updated_twitter_cache();
		}
		for ($fb_transient = $tweets_offset; $fb_transient < $nbr_of_tweets + $tweets_offset; $fb_transient++)
		{
			$tweet = get_transient( 'fb-get-tweets-'.$fb_transient);
			if ($tweet === false)
				return false;
			$tweets_res[$fb_transient] = $tweet;
		}
	}
	
	return $tweets_res;
}

// Get Tweets and save them
function updated_twitter_cache()
{
	clean_transient_tweets();
	$tweet_count = get_option('pt_twitter_tweet_count');
	$refresh_rate = get_option('pt_twitter_refresh_rate');
	if ($tweet_count > 0)
	{
		$tweets = fb_getweets_get_tweets($tweet_count);
		if (count($tweets) > 0)
		{
			foreach ($tweets as $key => $tweet) {
				set_transient( 'fb-get-tweets-'.$key, $tweet, $refresh_rate);
			}
		}
	}
}

// Clean saved transiant to avoid loading old cached data
function clean_transient_tweets()
{
	global $wpdb;
	$results = $wpdb->get_results("DELETE FROM `wp_options` WHERE (`option_name` LIKE '%_transient_fb-get-tweets%' OR  `option_name` LIKE '%_transient_timeout_fb-get-tweets%')", OBJECT );
	
	return true;
}

// Add Shortcode
function shortcode_fb_get_tweets( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'count' => false,
			'offset' => 0,
		), $atts )
	);

	return format_tweet($count, $offset);
}
add_shortcode( 'fb-get-tweets', 'shortcode_fb_get_tweets' );

?>