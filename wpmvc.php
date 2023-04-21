<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpmvc_main;
global $path_to_error;
global $framework_routes;

$wpmvc_main = __FILE__;
$path_to_error = __DIR__ . '/error/';

include 'config.php';
include 'include/helpers.php';
include 'classes/Autoload.php';
include 'classes/Route.php';
include 'classes/Encrypt.php';
include 'classes/Response.php';
include 'classes/Setup.php';
include 'classes/Validate.php';

function run_wpmvc() {
	
	add_rewrite_rule( '^'.PATHNAME.'/?$','index.php?'.ROUTE.'=/','top' );
	add_rewrite_rule( '^'.PATHNAME.'/(.*)?', 'index.php?'.ROUTE.'=$matches[1]','top' );

	new \Levlane\Route;
	new \Levlane\Setup;
	new \Levlane\Autoload;
}

add_action('init', 'run_wpmvc');
