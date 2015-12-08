<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://purecharity.com
 * @since      1.0.0
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Purecharity_Wp_Fundraisers
 * @subpackage Purecharity_Wp_Fundraisers/admin
 * @author     Pure Charity <dev@purecharity.com>
 */
class Purecharity_Wp_Fundraisers_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
	/**
	 * Add the Plugin Settings Menu.
	 *
	 * @since    1.0.0
	 */
	function add_admin_menu(  ) { 
		add_options_page( 'PureCharity&#8482; Fundraisers Settings', 'PureCharity&#8482; Fundraisers', 'manage_options', 'purecharity_fundraisers', array('Purecharity_Wp_Fundraisers_Admin', 'options_page') );
	}

	/**
	 * Checks for the existence of the fundraisers plugin settings.
	 *
	 * @since    1.0.0
	 */
	public static function settings_exist(  ) { 
		if( false == get_option( 'purecharity_fundraisers_settings' ) ) { 
			add_option( 'purecharity_fundraisers_settings' );
		}
	}

	/**
	 * Initializes the settings page options.
	 *
	 * @since    1.0.0
	 */
	public static function settings_init(  ) 
	{
		register_setting( 'pfPluginPage', 'purecharity_fundraisers_settings' );

		add_settings_section(
			'purecharity_fundraisers_pfPluginPage_section', 
			__( 'General settings', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'settings_section_callback'),
			'pfPluginPage'
		);

		add_settings_field( 
			'plugin_color', 
			__( 'Main Theme Color', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'main_color_render'), 
			'pfPluginPage', 
			'purecharity_fundraisers_pfPluginPage_section' 
		);

		add_settings_field( 
			'single_view_template', __( 'Single view template', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'single_view_template_render'), 
			'pfPluginPage', 
			'purecharity_fundraisers_pfPluginPage_section' 
		);

		add_settings_section(
			'purecharity_fundraisers_display_pfPluginPage_section', 
			__( 'Display settings', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'display_settings_section_callback'),
			'pfPluginPage'
		);

		add_settings_field( 
			'live_filter', __( 'Display Live Filter', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'live_filter_render'), 
			'pfPluginPage', 
			'purecharity_fundraisers_display_pfPluginPage_section' 
		);

		add_settings_field( 
			'fundraise_cause', __( 'Hide "Fundraise for this cause" link', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'fundraise_cause_render'), 
			'pfPluginPage', 
			'purecharity_fundraisers_display_pfPluginPage_section' 
		);

		add_settings_field( 
			'backers_tab', __( 'Hide the Backers Tab', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'backers_tab_render'), 
			'pfPluginPage', 
			'purecharity_fundraisers_display_pfPluginPage_section' 
		);

		add_settings_field( 
			'updates_tab', __( 'Hide the Updates Tab', 'wordpress' ), 
			array('Purecharity_Wp_Fundraisers_Admin', 'updates_tab_render'), 
			'pfPluginPage', 
			'purecharity_fundraisers_display_pfPluginPage_section' 
		);
	}

	/**
	 * Renders the fundraise for this cause.
	 *
	 * @since    1.1
	 */
	public static function fundraise_cause_render(  ) { 
		$options = get_option( 'purecharity_fundraisers_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_fundraisers_settings[fundraise_cause]" 
			<?php echo (isset($options['fundraise_cause'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the backers tab.
	 *
	 * @since    1.1
	 */
	public static function backers_tab_render(  ) { 
		$options = get_option( 'purecharity_fundraisers_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_fundraisers_settings[backers_tab]" 
			<?php echo (isset($options['backers_tab'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the updates tab.
	 *
	 * @since    1.1
	 */
	public static function updates_tab_render(  ) { 
		$options = get_option( 'purecharity_fundraisers_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_fundraisers_settings[updates_tab]" 
			<?php echo (isset($options['updates_tab'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the updates.
	 *
	 * @since    1.0.4
	 */
	public static function single_view_template_render(  ) { 
		$options = get_option( 'purecharity_fundraisers_settings' );
		$templates = purecharity_get_templates();
		?>
		<select name="purecharity_fundraisers_settings[single_view_template]">
			<option value="">Inherit from the listing page</option>
			<?php foreach($templates as $key => $template){ ?>
				<option <?php echo $template == @$options['single_view_template'] ? 'selected' : '' ?> value="<?php echo $template; ?>"><?php echo "$key ($template)" ?></option>
			<?php } ?>
		</select>
		<?php
	}

	/**
	 * Renders the live filter.
	 *
	 * @since    1.0.0
	 */
	public static function live_filter_render(  ) { 
		$options = get_option( 'purecharity_fundraisers_settings' );
		?>
		<input 
			type="checkbox" 
			name="purecharity_fundraisers_settings[live_filter]" 
			<?php echo (isset($options['live_filter'])) ? 'checked' : '' ?>
			value="true">
		<?php
	}

	/**
	 * Renders the main theme color picker.
	 *
	 * @since    1.0.0
	 */
	public static function main_color_render(  ) { 

		$options = get_option( 'purecharity_fundraisers_settings' );
		?>
		<input type="text" name="purecharity_fundraisers_settings[plugin_color]" id="main_color" value="<?php echo @$options['plugin_color']; ?>">

	<?php

	}

	/**
	 * Callback for use with Settings API.
	 *
	 * @since    1.0.0
	 */
	public static function settings_section_callback(  ) 
	{ 
		echo __( 'General settings for the Pure Charity Fundraisers plugin.', 'wordpress' );
	}

	/**
	 * Callback for use with Settings API.
	 *
	 * @since    1.1
	 */
	public static function display_settings_section_callback(  ) 
	{ 
		echo __( 'Display settings for the Pure Charity Fundraisers plugin.', 'wordpress' );
	}

	
	/**
	 * Creates the options page.
	 *
	 * @since    1.0.0
	 */
	public static function options_page()
	{
    ?>
    <div class="wrap">
      <form action="options.php" method="post" class="pure-settings-form">
				<?php
					echo '<img align="left" src="' . plugins_url( 'purecharity-wp-base/public/img/purecharity.png' ) . '" > ';
				?>
				<h2 style="padding-left:100px;padding-top: 20px;padding-bottom: 50px;border-bottom: 1px solid #ccc;">PureCharity&#8482; Fundraisers Settings</h2>
				
				<?php
				settings_fields( 'pfPluginPage' );
				do_settings_sections( 'pfPluginPage' );
				submit_button();
				?>
				
			</form>
    </div>
    <?php
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );

	}

}
