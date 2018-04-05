<?php
if( !function_exists( 'acmephoto_demo_nav_data') ){
	function acmephoto_demo_nav_data(){
		$demo_navs = array(
			'primary'  => 'Primary Menu'
		);
		return $demo_navs;
	}
}
add_filter('acme_demo_setup_nav_data','acmephoto_demo_nav_data');