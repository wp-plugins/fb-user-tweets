=== FB User Tweets ===
Contributors: zdevil09
Tags: Twitter, Feed, API
Requires at least: 4.0
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv2

FB User Tweets plugin uses the Twitter API to get tweets from a user's timeline and cache them. You can use a function or shortcode to print them.

== Description ==
FB Get Tweets plugin uses the Twitter API to get tweets from a user's timeline and display them on your WordPress site. You need to create a Twitter application and generate a user access token.
FB Get Tweets Plugin provides a nice interface to add the settings, view cached tweets and style the output.
The plugin saves the tweets using WordPress transient. You can set the refresh time (expiration) from the settings interface.

== Installation ==
1. Upload the fb-gettweets folder to the /wp-content/plugins/ directory
2. Activate the FB Get Tweets plugin from the 'Plugins' menu in WordPress
3. Create a Twitter app https://apps.twitter.com/
4. Generate Access Token
5. Configure the plugin (Twitter API KEY and User Token) by going to the FB Tweets Settings
6. Configure the plugin stye by going to the FB Tweets Style
7. Configure the plugin stye by going to the FB Tweets Style
8. In your template file, call function fb_get_tweets() for raw data (objects) and loop through them or format_tweet() for html. You can also use [fb-get-tweets  count="3" offset="0"] inside a WordPress page / post content.

== Screenshots ==
1. Add your Twitter app settings
2. Preview cached tweets
3. Few settings to style the output.
