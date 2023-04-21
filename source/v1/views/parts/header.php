<?php
	if ( ! defined('ABSPATH') ){ die(); }
	
	global $avia_config;
	
	$lightbox_option = avia_get_option( 'lightbox_active' );
	$avia_config['use_standard_lightbox'] = empty( $lightbox_option ) || ( 'lightbox_active' == $lightbox_option ) ? 'lightbox_active' : 'disabled';
	/**
	 * Allow to overwrite the option setting for using the standard lightbox
	 * Make sure to return 'disabled' to deactivate the standard lightbox - all checks are done against this string
	 * 
	 * @added_by Günter
	 * @since 4.2.6
	 * @param string $use_standard_lightbox				'lightbox_active' | 'disabled'
	 * @return string									'lightbox_active' | 'disabled'
	 */
	$avia_config['use_standard_lightbox'] = apply_filters( 'avf_use_standard_lightbox', $avia_config['use_standard_lightbox'] );

	$style 					= $avia_config['box_class'];
	$responsive				= avia_get_option('responsive_active') != "disabled" ? "responsive" : "fixed_layout";
	$blank 					= isset($avia_config['template']) ? $avia_config['template'] : "";	
	$av_lightbox			= $avia_config['use_standard_lightbox'] != "disabled" ? 'av-default-lightbox' : 'av-custom-lightbox';
	$preloader				= avia_get_option('preloader') == "preloader" ? 'av-preloader-active av-preloader-enabled' : 'av-preloader-disabled';
	$sidebar_styling 		= avia_get_option('sidebar_styling');
	$filterable_classes 	= avia_header_class_filter( avia_header_class_string() );
	$av_classes_manually	= "av-no-preview"; /*required for live previews*/
	$av_classes_manually   .= avia_is_burger_menu() ? " html_burger_menu_active" : " html_text_menu_active";
	
	/**
	 * @since 4.2.3 we support columns in rtl order (before they were ltr only). To be backward comp. with old sites use this filter.
	 */
	$rtl_support			= 'yes' == apply_filters( 'avf_rtl_column_support', 'yes' ) ? ' rtl_columns ' : '';
	
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="depositor-directory">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<link rel="preload" href="https://fonts.googleapis.com/css?family=Montserrat:300i,400,500,600,800&display=swap" as="style" />
<link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/webfonts/Isabel-Bold.otf" as="font" crossorigin />
<link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/webfonts/Isabel-Bold.woff2" as="font" crossorigin />
<link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/webfonts/Isabel-Bold.woff" as="font" crossorigin />
<?php
/*
 * outputs a rel=follow or nofollow tag to circumvent google duplicate content for archives
 * located in framework/php/function-set-avia-frontend.php
 */
 if (function_exists('avia_set_follow')) { echo avia_set_follow(); }

?>


<!-- mobile setting -->
<?php

if( strpos($responsive, 'responsive') !== false ) echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
?>


<!-- Scripts/CSS and wp_head hook -->
<?php
/* Always have wp_head() just before the closing </head>
 * tag of your theme, or you will break many plugins, which
 * generally use this hook to add elements to <head> such
 * as styles, scripts, and meta tags.
 */

wp_head();

?>

	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	
	<link rel="stylesheet" href="https://use.typekit.net/ndx1yfy.css">
		
	<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2.js" defer></script>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/hubspot-load.min.js" defer></script>
	
	<style>
		html.depositor-directory{
		  height: 100%;
		}
		body.depositor-directory {
		  min-height: 100%;
		  background-color: #102937
		}
		span[id^="extensions_message"] {
		  font-size: 0.9em;
		  padding: 7px 0;
		  display: inline-block;
		  color: #7a7a7a;
		  font-style: italic;
		}

		.clearfix::after {
		  content: "";
		  clear: both;
		  display: table;
		}

		#depositor-directory-form label {
		  display: block;
		  padding-bottom: 10px;
		}

		#depositor-directory-form .form-footer {
		  display: flex;
		  flex-direction: row;
		  justify-content: flex-end;
		}

		#depositor-directory-header p {
		  font-size: 1.4em;
		}

		#depositor-directory-header h1 {
		  margin-bottom:0;
		  text-align: center;
		  color: #fff;
		}

		#depositor-directory-header {
		  padding-top: 30px;
		  padding-bottom: 30px;
		  background-color: #760c16;
		}

		#main.depositor-directory {
		  padding: 50px 0 !important;
		  background-color:#f3f3f3;
		}

		#main.depositor-directory .card {
		  padding: 15px;
		  border: 1px solid #efefef;
		  background-color: #fff;
		  margin: 0 10px 20px;
		  border-radius: 3px;
		  box-shadow: 0px 0px 3px -1px #959595;
		}

		.depositor-directory a {
		  color: rgb(118, 12, 22);
		}

		.depositor-directory 
		.tooltip:hover + .tooltip-popup {
		  display: block;
		  opacity: 1;
		}

		.depositor-directory .tooltip {
		  display: inline-block;
		  width: 17px;
		  height: 17px;
		  background-color: #ababab;
		  color: #FFF !important;
		  border-radius: 999px;
		  text-align: center;
		  font-size: 0.9em !important;
		  line-height: 17px;
		  margin-left: 3px;
		  cursor:pointer;
		  font-weight: bold !important;
		}

		.depositor-directory .tooltip-popup {
		  width: 320px;
		  background-color: aliceblue;
		  padding: 8px;
		  position: absolute;
		  display: none;
		  opacity: 0;
		  transition: all 0.4s linear;
		}

		.depositor-directory .error-container {
		  margin-bottom: 20px;
		}
		.depositor-directory .error-label {
		  margin-bottom: 10px;
		  background-color: #ff7474;
		  color: #fff;
		  font-weight: 500;
		  font-size: 1.1em;
		  padding: 4px 8px;
		}

		#header-depositor #header_main {
		  background-color: #fff;
		  margin-bottom: 0 !important;
		  top: 0 !important;
		  padding-top: 20px;
		}

		#wrap_all #header-depositor #header_main .logo img {
		  max-width: 100%;
		}

		#wrap_all #header-depositor #header_main .logo {
		  top: 50%;
		  left: 50%;
		  transform: translate(-50%,-50%) !important;
		  max-width: none;
		  bottom: auto;
		  right: auto;
		  text-align: initial;
		  margin: 0;
		  position: absolute;
		  overflow: hidden;
		  z-index: 999;
		}

		div#header_main {
		  padding-bottom: 20px;
		  border: none;
		}
	</style>
	
</head>




<body id="top" class="depositor-directory">

	<?php 
	
	do_action( 'ava_after_body_opening_tag' );
		
	if("av-preloader-active av-preloader-enabled" === $preloader)
	{
		echo avia_preload_screen(); 
	}
		
	?>

	<div id='wrap_all'>

		<header id="header-depositor" style="position: relative;">
			<div id="header_main" class="container_wrap container_wrap_logo">
				<div class="container">
					<div class="inner-container">
						<span class="logo"><a href="https://searstone.flywheelstaging.com/" style="max-height: 120px;"><img height="100" width="300" src="/wp-content/uploads/2020/02/Searstone-Logo-sm.png" alt="SearStone" style="max-height: 120px;"></a></span>
					</div>
				</div>
			</div>
		</header>