<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://purecharity.com
 * @since             1.0.0
 * @package           Purecharity_Wp_Fundraisers
 *
 * @wordpress-plugin
 * Plugin Name:       Pure Charity Fundraisers
 * Plugin URI:        http://purecharity.com/
 * Description:       Plugin with a collection of shortcodes and template tags to display Pure Charity Fundraisers
 * Version:           1.2.1
 * Author:            Pure Charity
 * Author URI:        http://purecharity.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       purecharity-wp-fundraisers
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The template tags.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/template_tags.php';


/**
 * The paginator.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/paginator.class.php';

/**
 * The shortcode.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.class.php';

/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/activator.class.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/deactivator.class.php';

/** This action is documented in includes/purecharity-wp-fundraisers-activator.class.php */
register_activation_hook( __FILE__, array( 'Purecharity_Wp_Fundraisers_Activator', 'activate' ) );

/** This action is documented in includes/purecharity-wp-fundraisers-deactivator.class.php */
register_deactivation_hook( __FILE__, array( 'Purecharity_Wp_Fundraisers_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/purecharity-wp-fundraisers.class.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_purecharity_wp_fundraisers() {

	$plugin = new Purecharity_Wp_Fundraisers();
	$plugin->run();

}
run_purecharity_wp_fundraisers();
register_activation_hook( __FILE__, array( 'Purecharity_Wp_Fundraisers', 'activation_check' ) );


/**
 * Force the use of a specific template
 *
 * @since    1.0.4
 */
function fr_force_template() {
	$options = get_option( 'purecharity_fundraisers_settings' );
  include(TEMPLATEPATH . '/' . $options['single_view_template']);
  exit;
}

/**
 * Sets the meta tags for the facebook sharing.
 *
 * @since    1.1.5
 */
add_action( 'wp_head', 'set_fr_meta_tags' );
function set_fr_meta_tags(){
	if(isset($_GET['slug']) && false){
		$base_plugin = new Purecharity_Wp_Base();
		$fundraiser = $base_plugin->api_call('fundraisers/show?slug='. $_GET['slug'])->fundraiser;
		echo '
			<meta property="og:title" content="'.strip_tags($fundraiser->name).'">
			<meta property="og:image" content="'.$fundraiser->images->large.'">
			<meta property="og:description" content="'.strip_tags($fundraiser->about).'">
		' . "\n";
	}
}

/*
 * Plugin updater using GitHub
 *
 * Auto Updates through GitHub
 *
 * @since   1.1.3
 */
add_action( 'init', 'purecharity_wp_fundraisers_updater' );
function purecharity_wp_fundraisers_updater() {
  if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin
    $fr_config = array(
      'slug' => plugin_basename( __FILE__ ),
      'proper_folder_name' => 'purecharity-wp-fundraisers',
      'api_url' => 'https://api.github.com/repos/purecharity/pure-fundraisers',
      'raw_url' => 'https://raw.githubusercontent.com/purecharity/pure-fundraisers/master/purecharity-wp-fundraisers/',
      'github_url' => 'https://github.com/purecharity/pure-fundraisers',
      'zip_url' => 'https://github.com/purecharity/pure-fundraisers/archive/master.zip',
      'sslverify' => true,
      'requires' => '3.0',
      'tested' => '3.3',
      'readme' => 'README.md',
      'access_token' => '',
    );
    new WP_GitHub_Updater( $fr_config );
  }
}
