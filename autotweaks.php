<?php
/**
 * Plugin Name:     AutoTweaks
 * Plugin URI:      https://wordpress.org/plugins/autotweaks
 * Description:     AutoTweaks configures a series of default options to WordPress
 * Author:          Luis Celadita
 * Author URI:      https://www.luisceladita.com
 * Text Domain:     autotweaks
 * Version:         1.4
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Hardening HTTP Security Headers
is_admin() || add_action('send_headers', function(){
  header( "X-Frame-Options: SAMEORIGIN" );
  header( "X-XSS-Protection: 1;mode=block" );
  header( "Content-Security-Policy: default-src 'self' https: data:; script-src 'self' https: 'unsafe-inline'; style-src 'self' https: 'unsafe-inline'; font-src 'self' https: data:; object-src 'none';" );
  header( "Referrer-Policy: no-referrer-when-downgrade" );
  header( "X-Content-Type-Options: nosniff" );
  header( "Strict-Transport-Security: max-age=15552000" );
  //header( "Feature-Policy: geolocation 'self'; microphone 'none'" );
  //header( "Permissions-Policy: geolocation=(self), microphone=()" );
}, 100);

if ( ! is_admin() ) {
  //Remove Really Simple Discovery (RSD) links. They are used for automatic pingbacks.
  remove_action( 'wp_head', 'rsd_link' );
  //Remove the link to wlwmanifest.xml. It is needed to support Windows Live Writer.
  remove_action( 'wp_head', 'wlwmanifest_link' );
  //Remove Automatics RSS links. RSS will still work, but you will need to provide your own links.
  remove_action( 'wp_head', 'feed_links' );
  remove_action( 'wp_head', 'feed_links_extra' );
  //Remove generator tag from RSS feeds.
  remove_action( 'atom_head', 'the_generator' );
  remove_action( 'comments_atom_head', 'the_generator' );
  remove_action( 'rss_head', 'the_generator' );
  remove_action( 'rss2_head', 'the_generator' );
  remove_action( 'commentsrss2_head', 'the_generator' );
  remove_action( 'rdf_header', 'the_generator' );
  remove_action( 'opml_head', 'the_generator' );
  remove_action( 'app_head', 'the_generator' );
  //Remove the next and previous post links from the header
  remove_action( 'wp_head', 'adjacent_posts_rel_link' );
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
  //Remove the shortlink url from header
  remove_action( 'wp_head', 'wp_shortlink_wp_head' );
  remove_action( 'template_redirect', 'wp_shortlink_header' );
  //Remove WordPress and WooCommerce meta generator tags. Used by attackers to detect the WordPress version.
  remove_action( 'wp_head', 'wp_generator' );
  //Removes a block of inline CSS used by old themes from the header
  add_filter( 'show_recent_comments_widget_style', '__return_false' );
  //Removes dns-prefetch links from the header
  remove_action( 'wp_head', 'wp_resource_hints' );
  //Removes the filter that converts Wordpress to WordPress in every dang title, content or comment text.
  remove_filter( 'the_title', 'capital_P_dangit' );
  remove_filter( 'the_content', 'capital_P_dangit' );
  remove_filter( 'comment_text', 'capital_P_dangit' );
  //Removes Post relational links
  remove_action( 'wp_head', 'index_rel_link');
  remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // REMOVE RANDOM LINK POST.
  remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // REMOVE PARENT POST LINK.
  //URLs in comments are converted to links by default. This feature is often exploited by spammers.
  remove_filter( 'comment_text', 'make_clickable', 9 );
  // REMOVE GENERATOR NAME FROM RSS FEEDS.
  add_filter( 'the_generator', '__return_false' );
}

//Disable the plugins and theme editor. A mostly useless tool that can be very dangerous in the wrong hands.
// if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
//   define( 'DISALLOW_FILE_EDIT', true );
// }

//Starting with 4.7, WordPress tries to make thumbnails from each PDF you upload, potentially crashing your server if GhostScript and ImageMagick aren't properly configured. This option disables PDF thumbnails.
add_filter(
  'fallback_intermediate_image_sizes',
  function() {
    return array();
  }
);

//By default, heartbeat makes a post call every 15 seconds on post edit pages. Change to 60 seconds (less CPU usage).
add_filter(
  'heartbeat_settings',
  function ( $settings ) {
    $settings['interval'] = 60;
    return $settings;
  }
);

//Remove jQuery-migrate that provides diagnostics that can simplify upgrading to new versions of jQuery.
function wpms_disable_jquery_migrate( $scripts ) {
  if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
    $script = $scripts->registered['jquery'];    
    if ( $script->deps ) { // Check whether the script has any dependencies
      $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
    }
  }
}
add_action( 'wp_default_scripts', 'wpms_disable_jquery_migrate' );

//Since WordPress 4.4, oEmbed is installed and available by default.
function wpms_disable_embed(){
  wp_deregister_script( 'wp-embed' );
  wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'wpms_disable_embed' );

//Remove dashicons
function wpms_disable_dashicons() { 
    if ( ! is_user_logged_in() ) {
        wp_dequeue_style( 'dashicons' );
        wp_deregister_style( 'dashicons' );
    }
}
add_action( 'wp_print_styles', 'wpms_disable_dashicons' );

//Disable self pingback
function wpms_disable_pingback( &$links ) {
  foreach ( $links as $l => $link )
  if ( 0 === strpos( $link, get_option( 'home' ) ) )
  unset($links[$l]);
}
add_action( 'pre_ping', 'wpms_disable_pingback' );

//Limit Post Revisions
if ( defined( 'WP_POST_REVISIONS' ) && ( WP_POST_REVISIONS !== false ) ) {
  add_filter(
    'wp_revisions_to_keep',
    function( $num, $post ) {
      return 1;
    },
    10,
    2
  );
}

//Disable the XML-RPC interface.
//Remove REST API info from head and headers.
add_filter( 'xmlrpc_enabled', '__return_false' );
// Hide xmlrpc.php in HTTP response headers.
add_filter(
  'wp_headers',
  function( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
  }
);
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter(
  'xmlrpc_methods',
  function( $methods ) {
    unset( $methods['pingback.ping'] );
    return $methods;
  }
);

//prevent all page titles from appearing on any page
// function ele_disable_page_title( $return ) {
//   return false;
// }
// add_filter( 'hello_elementor_page_title', 'ele_disable_page_title' );

//Add Post ID to Posts and Pages Admin Columns
add_filter('manage_posts_columns', 'posts_columns_id', 5);
add_action('manage_posts_custom_column', 'posts_custom_id_columns', 5, 2);
add_filter('manage_pages_columns', 'posts_columns_id', 5);
add_action('manage_pages_custom_column', 'posts_custom_id_columns', 5, 2); 
function posts_columns_id($defaults){
   $defaults['wps_post_id'] = __('ID');
   return $defaults;
}
function posts_custom_id_columns($column_name, $id){
   if($column_name === 'wps_post_id'){
           echo $id;
   }
}

//Add Category ID  to Posts and Pages Admin Columns
foreach ( get_taxonomies() as $taxonomy ) {
   add_action( "manage_edit-${taxonomy}_columns",          't5_add_col' );
   add_filter( "manage_edit-${taxonomy}_sortable_columns", 't5_add_col' );
   add_filter( "manage_${taxonomy}_custom_column",         't5_show_id', 10, 3 );
}
add_action( 'admin_print_styles-edit-tags.php', 't5_tax_id_style' );
function t5_add_col( $columns ){return $columns + array ( 'tax_id' => 'ID' );}
function t5_show_id( $v, $name, $id ){return 'tax_id' === $name ? $id : $v;}
function t5_tax_id_style(){print '<style>#tax_id{width:4em}</style>';}
