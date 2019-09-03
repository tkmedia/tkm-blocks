<?php
/**
 * General
 *
 * @package      TKMBlocks
 * @author       Tal Katz TKMedia.co.il
 * @since        1.0.0
 * @license      GPL-2.0+
**/
/**
 * Dont Update the Plugin
 * If there is a plugin in the repo with the same name, this prevents WP from prompting an update.
 *
 * @since  1.0.0
 * @author Jon Brown
 * @param  array $r Existing request arguments
 * @param  string $url Request URL
 * @return array Amended request arguments
 */
function tkm_dont_update_core_func_plugin( $r, $url ) {
  if ( 0 !== strpos( $url, 'https://api.wordpress.org/plugins/update-check/1.1/' ) )
    return $r; // Not a plugin update request. Bail immediately.
    $plugins = json_decode( $r['body']['plugins'], true );
    unset( $plugins['plugins'][plugin_basename( __FILE__ )] );
    $r['body']['plugins'] = json_encode( $plugins );
    return $r;
 }
add_filter( 'http_request_args', 'tkm_dont_update_core_func_plugin', 5, 2 );
/**
 * Author Links on CF Plugin
 *
 */
function tkm_author_links_on_cf_plugin( $links, $file ) {
	if ( strpos( $file, 'core-blocks.php' ) !== false ) {
		$links[1] = 'By <a href="https://www.tkmedia.co.il">TKMedia Tal Katz</a>';
    }
    return $links;
}
add_filter( 'plugin_row_meta', 'tkm_author_links_on_cf_plugin', 10, 2 );
// Don't let WPSEO metabox be high priority
add_filter( 'wpseo_metabox_prio', function(){ return 'low'; } );
/**
 * Remove WPSEO Notifications
 *
 */
function tkm_remove_wpseo_notifications() {
	if( ! class_exists( 'Yoast_Notification_Center' ) )
		return;
	remove_action( 'admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
	remove_action( 'all_admin_notices', array( Yoast_Notification_Center::get(), 'display_notifications' ) );
}
add_action( 'init', 'tkm_remove_wpseo_notifications' );
/**
 * WPForms, default large field size
 *
 */
function tkm_wpforms_default_large_field_size( $field ) {
        if ( empty( $field['size'] ) ) {
            $field['size'] = 'large';
        }
        return $field;
    }
add_filter( 'wpforms_field_new_default', 'tkm_wpforms_default_large_field_size' );
/**
 * Gravity Forms Domain
 *
 * Adds a notice at the end of admin email notifications
 * specifying the domain from which the email was sent.
 *
 * @param array $notification
 * @param object $form
 * @param object $entry
 * @return array $notification
 */
function tkm_gravityforms_domain( $notification, $form, $entry ) {
	if( $notification['name'] == 'Admin Notification' ) {
		$notification['message'] .= 'Sent from ' . home_url();
	}
	return $notification;
}
add_filter( 'gform_notification', 'tkm_gravityforms_domain', 10, 3 );
/**
  * Exclude No-index content from search
  *
  */
function tkm_exclude_noindex_from_search( $query ) {
	if( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
		$meta_query = empty( $query->query_vars['meta_query'] ) ? array() : $query->query_vars['meta_query'];
		$meta_query[] = array(
			'key' => '_yoast_wpseo_meta-robots-noindex',
			'compare' => 'NOT EXISTS',
		);
		$query->set( 'meta_query', $meta_query );
	}
}
add_action( 'pre_get_posts', 'tkm_exclude_noindex_from_search' );
/**
 * Pretty Printing
 *
 * @since 1.0.0
 * @author Chris Bratlien
 * @param mixed $obj
 * @param string $label
 * @return null
 */
function tkm_pp( $obj, $label = '' ) {
	$data = json_encode( print_r( $obj,true ) );
	?>
	<style type="text/css">
		#bsdLogger {
		position: absolute;
		top: 30px;
		right: 0px;
		border-left: 4px solid #bbb;
		padding: 6px;
		background: white;
		color: #444;
		z-index: 999;
		font-size: 1.25em;
		width: 400px;
		height: 800px;
		overflow: scroll;
		}
	</style>
	<script type="text/javascript">
		var doStuff = function(){
			var obj = <?php echo $data; ?>;
			var logger = document.getElementById('bsdLogger');
			if (!logger) {
				logger = document.createElement('div');
				logger.id = 'bsdLogger';
				document.body.appendChild(logger);
			}
			////console.log(obj);
			var pre = document.createElement('pre');
			var h2 = document.createElement('h2');
			pre.innerHTML = obj;
			h2.innerHTML = '<?php echo addslashes($label); ?>';
			logger.appendChild(h2);
			logger.appendChild(pre);
		};
		window.addEventListener ("DOMContentLoaded", doStuff, false);
	</script>
	<?php
}


class TKM_ACF_Customizations {
	
	public function __construct() {

		// Only allow fields to be edited on development
		if ( ! defined( 'WP_LOCAL_DEV' ) || ! WP_LOCAL_DEV ) {
			add_filter( 'acf/settings/show_admin', '__return_false' );
		}

		// Save fields in functionality plugin
		add_filter( 'acf/settings/save_json', array( $this, 'get_local_json_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'add_local_json_path' ) );

		// Register options page
		//add_action( 'init', array( $this, 'register_options_page' ) );

		// Register Blocks
		//add_action('acf/init', array( $this, 'register_blocks' ) );

	}

	/**
	 * Define where the local JSON is saved
	 *
	 * @return string
	 */
	public function get_local_json_path() {
		return TKM_DIR . '/acf-json';
	}

	/**
	 * Add our path for the local JSON
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function add_local_json_path( $paths ) {
		$paths[] = TKM_DIR . '/acf-json';

		return $paths;
	}

	/**
	 * Register Options Page
	 *
	 */
	function register_options_page() {
	    if ( function_exists( 'acf_add_options_page' ) ) {
	        acf_add_options_page( array(
	        	'title'      => __( 'Site Options', 'core-functionality' ),
	        	'capability' => 'manage_options',
	        ) );
	    }
	}

	/**
	 * Register Blocks
	 * Categories: common, formatting, layout, widgets, embed
	 * Dashicons: https://developer.wordpress.org/resource/dashicons/
	 * ACF Settings: https://www.advancedcustomfields.com/resources/acf_register_block/
	 */
	function register_blocks() {

		if( ! function_exists('acf_register_block_type') )
			return;

		acf_register_block_type( array(
			'name'				=> 'features',
			'title'				=> __( 'Features', 'core-functionality' ),
			'render_template'		=> 'partials/block-features.php',
			'category'			=> 'formatting',
			'icon'				=> 'awards',
			'mode'				=> 'auto',
			'keywords'			=> array(),
		));
    
	}
}
new TKM_DIR_Customizations();