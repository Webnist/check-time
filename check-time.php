<?php
/*
Plugin Name: Check Time
Plugin URI: 
Description: WordPress の所定ポイントでの処理時間をフッターに表示します。
Author: jim912
Version: 0.1
Author URI: 
*/

class Check_Time {
	private $time = array();
	public function __construct() {
		if ( ! is_admin() ) {
			add_action( 'plugins_loaded', array( &$this, 'add_timer_hooks' ) );
			add_action( 'wp_footer'     , array( &$this, 'result' ), 9999 );
		}
	}
	
	
	public function add_timer_hooks() {
		if ( ! is_user_logged_in() ) { return; }
		$hooks = apply_filters( 'time-stop-hooks', array( 'init', 'parse_request', 'wp', 'get_header', 'wp_head', 'get_sidebar', 'get_footer', 'wp_footer' ) );
		foreach ( $hooks as $hook ) {
			add_action( $hook, array( &$this, 'check_time' ), 0 );
		}
	}


	public function check_time( $var = NULL ) {
		$this->time[] = current_filter() . ':' . timer_stop() . 's';
		return $var;
	}
	
	
	public function result() {
		echo '<ol class="wp-check-time">' . "\n";
		foreach ( $this->time as $time ) {
			echo '<li>' . esc_html( $time ) . "</li>\n";
		}
		echo '</ol>' . "\n";
	}
}
new Check_Time;