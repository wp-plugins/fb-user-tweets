<?php
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Tweets_List_Table extends WP_List_Table
{
	function get_columns()
	{
		$columns = array(
			'id'          => 'ID',
			'created_at'       => 'Create At',
			'tweet' => 'Tweet',
			);

		return $columns;
	}

	function get_hidden_columns()
	{
		return array();
	}

	function get_sortable_columns()
	{
		return array();
	}

	function prepare_items()
	{
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = $this->table_data();

		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $data;
	}

	function column_default( $item, $column_name )
	{
		switch( $column_name ) {
			case 'id':
			case 'created_at':
			case 'tweet':
			return $item[ $column_name ];

			default:
			return print_r( $item, true ) ;
		}
	}

	function table_data()
	{
		$tweets = fb_get_tweets();
		$data = array();


		$fb_tweet_time_type = get_option("fb_tweet_time_type");

		foreach ($tweets as $key => $tweet) {
			$data[] = array(
				'id'          => $key,
				'created_at'   => ($fb_tweet_time_type === 'time_ago' ? twitter_time($tweet->created_at) : $tweet->created_at),
				'tweet' => linkify_filtered($tweet->text)
				);
		}

		return $data;
	}
}
?>
<div class="wrap">
	<?php 
	$twitterListTable = new Tweets_List_Table();
	$twitterListTable->prepare_items();
	?>
	<div id="icon-users" class="icon32"></div>
	<h2>Cached Tweets</h2>
	<?php $twitterListTable->display(); ?>
</div>