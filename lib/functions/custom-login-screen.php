<?php
/**
 * Custom Login
 *
 * This file customizes the WordPress default login screen to client branding.
 *
 * @package      Core_Functionality
 * @since        1.0.0
 * @link         https://github.com/capwebsolutions/penncat-core-functionality
 * @author       Matt Ryan <matt@capwebsolutions.com>
 * @copyright    Copyright (c) 2023, Matt Ryan
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


 /**
 * Customize login screen with site logo replacing WordPress logo.
 *   New logo placed in the assets/images folder within Core Functionality plugin or
 *   /images folder in theme. Theme takes precedence. 
 *   Image file named site-login-logo.png 
 *   Image recommended size  80 x 80 pixels and compressed. 
 */
function capweb_login_logo() { 
    // Image file path possibilities for site logo. In plugin or in theme. 
    $default_image_plugin = trailingslashit(CORE_FUNCTIONALITY_PLUGIN_URI) .'assets/images/site-login-logo.png';
    $default_image_theme = trailingslashit(CORE_FUNCTIONALITY_THEME_DIR) .'images/site-login-logo.png';

    $default_image = $default_image_theme;
    If ( ! file_exists( $default_image_theme ) ) $default_image = $default_image_plugin;

	?>
    <style type="text/css">
        #login h1 a, 
        .login h1 a {
            background-image: url(<?php echo $default_image ?>);
            height:65px;
            width:320px;
            background-size: 320px 65px;
            background-repeat: no-repeat;
        	padding-bottom: 30px;
        }
        body.login {
            background: rgb(84,110,145);
            background: radial-gradient(circle, rgba(84,110,145,1) 0%, rgba(156,156,156,1) 100%);
        }
        #login p#nav a,
        #login p#backtoblog a {
            color: #ffffff;
        }
    </style>
	<?php 
	}
add_action( 'login_enqueue_scripts', 'capweb_login_logo' );
/**
 * Grab the address of the site to connect to new Logo 
 * 
 */

function capweb_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'capweb_login_logo_url' );
  
/** 
 * Grab the name of the site. 
 */
function capweb_login_logo_url_text() {
    return get_bloginfo( $show = 'name', $filter = 'raw' );
}
add_filter( 'login_headertext', 'capweb_login_logo_url_text' );