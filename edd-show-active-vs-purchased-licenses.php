<?php
/**
 * Plugin Name: EDD - Show Active vs Purchased Licenses
 * Description: Shows how many Purchased Licenses there are for a Download and of how many activations are available, how many are actually being used
 * Version: 0.1.0
 * Text Domain: edd-show-active-vs-purchased-licenses
 * Author: Real Big Plugins
 * Author URI: https://realbigplugins.com/
 * Contributors: d4mation
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EDD_Show_Active_vs_Purchased_Licenses' ) ) {

	/**
	 * Main EDD_Show_Active_vs_Purchased_Licenses class
	 *
	 * @since	  {{VERSION}}
	 */
	final class EDD_Show_Active_vs_Purchased_Licenses {
		
		/**
		 * @var			array $plugin_data Holds Plugin Header Info
		 * @since		{{VERSION}}
		 */
		public $plugin_data;
		
		/**
		 * @var			array $admin_errors Stores all our Admin Errors to fire at once
		 * @since		{{VERSION}}
		 */
		private $admin_errors;

		/**
		 * Get active instance
		 *
		 * @access	  public
		 * @since	  {{VERSION}}
		 * @return	  object self::$instance The one true EDD_Show_Active_vs_Purchased_Licenses
		 */
		public static function instance() {
			
			static $instance = null;
			
			if ( null === $instance ) {
				$instance = new static();
			}
			
			return $instance;

		}
		
		protected function __construct() {
			
			$this->setup_constants();
			$this->load_textdomain();
			
			if ( version_compare( get_bloginfo( 'version' ), '4.4' ) < 0 ) {
				
				$this->admin_errors[] = sprintf( _x( '%s requires v%s of %sWordPress%s or higher to be installed!', 'First string is the plugin name, followed by the required WordPress version and then the anchor tag for a link to the Update screen.', 'edd-show-active-vs-purchased-licenses' ), '<strong>' . $this->plugin_data['Name'] . '</strong>', '4.4', '<a href="' . admin_url( 'update-core.php' ) . '"><strong>', '</strong></a>' );
				
				if ( ! has_action( 'admin_notices', array( $this, 'admin_errors' ) ) ) {
					add_action( 'admin_notices', array( $this, 'admin_errors' ) );
				}
				
				return false;
				
			}

			if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
				
				$this->admin_errors[] = sprintf( __( '%s requires %s to be installed!', 'edd-show-active-vs-purchased-licenses' ), '<strong>' . $this->plugin_data['Name'] . '</strong>', '<a href="//wordpress.org/plugins/easy-digital-downloads/" title="Easy Digital Downloads" target="_blank">Easy Digital Downloads</a>' );
				
				if ( ! has_action( 'admin_notices', array( $this, 'admin_errors' ) ) ) {
					add_action( 'admin_notices', array( $this, 'admin_errors' ) );
				}
				
				return false;
				
			}

			if ( ! class_exists( 'EDD_Software_Licensing' ) ) {
				
				$this->admin_errors[] = sprintf( __( '%s requires %s to be installed!', 'edd-show-active-vs-purchased-licenses' ), '<strong>' . $this->plugin_data['Name'] . '</strong>', '<a href="//easydigitaldownloads.com/downloads/software-licensing/" title="Software Licensing" target="_blank">Software Licensing</a>' );
				
				if ( ! has_action( 'admin_notices', array( $this, 'admin_errors' ) ) ) {
					add_action( 'admin_notices', array( $this, 'admin_errors' ) );
				}
				
				return false;
				
			}
			
			$this->require_necessities();
			
			// Register our CSS/JS for the whole plugin
			add_action( 'init', array( $this, 'register_scripts' ) );
			
		}

		/**
		 * Setup plugin constants
		 *
		 * @access	  private
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		private function setup_constants() {
			
			// WP Loads things so weird. I really want this function.
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			
			// Only call this once, accessible always
			$this->plugin_data = get_plugin_data( __FILE__ );

			if ( ! defined( 'EDD_Show_Active_vs_Purchased_Licenses_VER' ) ) {
				// Plugin version
				define( 'EDD_Show_Active_vs_Purchased_Licenses_VER', $this->plugin_data['Version'] );
			}

			if ( ! defined( 'EDD_Show_Active_vs_Purchased_Licenses_DIR' ) ) {
				// Plugin path
				define( 'EDD_Show_Active_vs_Purchased_Licenses_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'EDD_Show_Active_vs_Purchased_Licenses_URL' ) ) {
				// Plugin URL
				define( 'EDD_Show_Active_vs_Purchased_Licenses_URL', plugin_dir_url( __FILE__ ) );
			}
			
			if ( ! defined( 'EDD_Show_Active_vs_Purchased_Licenses_FILE' ) ) {
				// Plugin File
				define( 'EDD_Show_Active_vs_Purchased_Licenses_FILE', __FILE__ );
			}

		}

		/**
		 * Internationalization
		 *
		 * @access	  private 
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		private function load_textdomain() {

			// Set filter for language directory
			$lang_dir = EDD_Show_Active_vs_Purchased_Licenses_DIR . '/languages/';
			$lang_dir = apply_filters( 'edd_show_active_vs_purchased_licenses_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'edd-show-active-vs-purchased-licenses' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'edd-show-active-vs-purchased-licenses', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/edd-show-active-vs-purchased-licenses/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/edd-show-active-vs-purchased-licenses/ folder
				// This way translations can be overridden via the Theme/Child Theme
				load_textdomain( 'edd-show-active-vs-purchased-licenses', $mofile_global );
			}
			else if ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/edd-show-active-vs-purchased-licenses/languages/ folder
				load_textdomain( 'edd-show-active-vs-purchased-licenses', $mofile_local );
			}
			else {
				// Load the default language files
				load_plugin_textdomain( 'edd-show-active-vs-purchased-licenses', false, $lang_dir );
			}

		}
		
		/**
		 * Include different aspects of the Plugin
		 * 
		 * @access	  private
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		private function require_necessities() {

			require_once EDD_Show_Active_vs_Purchased_Licenses_DIR . '/core/admin/admin-page.php';
			
		}
		
		/**
		 * Show admin errors.
		 * 
		 * @access	  public
		 * @since	  {{VERSION}}
		 * @return	  HTML
		 */
		public function admin_errors() {
			?>
			<div class="error">
				<?php foreach ( $this->admin_errors as $notice ) : ?>
					<p>
						<?php echo $notice; ?>
					</p>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		/**
		 * Register our CSS/JS to use later
		 * 
		 * @access	  public
		 * @since	  {{VERSION}}
		 * @return	  void
		 */
		public function register_scripts() {
			
			wp_register_style(
				'edd-show-active-vs-purchased-licenses',
				EDD_Show_Active_vs_Purchased_Licenses_URL . 'dist/assets/css/app.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : EDD_Show_Active_vs_Purchased_Licenses_VER
			);
			
			wp_register_script(
				'edd-show-active-vs-purchased-licenses',
				EDD_Show_Active_vs_Purchased_Licenses_URL . 'dist/assets/js/app.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : EDD_Show_Active_vs_Purchased_Licenses_VER,
				true
			);
			
			wp_localize_script( 
				'edd-show-active-vs-purchased-licenses',
				'eddShowActiveVsPurchasedLicenses',
				apply_filters( 'edd_show_active_vs_purchased_licenses_localize_script', array() )
			);
			
			wp_register_style(
				'edd-show-active-vs-purchased-licenses-admin',
				EDD_Show_Active_vs_Purchased_Licenses_URL . 'dist/assets/css/admin.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : EDD_Show_Active_vs_Purchased_Licenses_VER
			);
			
			wp_register_script(
				'edd-show-active-vs-purchased-licenses-admin',
				EDD_Show_Active_vs_Purchased_Licenses_URL . 'dist/assets/js/admin.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : EDD_Show_Active_vs_Purchased_Licenses_VER,
				true
			);
			
			wp_localize_script( 
				'edd-show-active-vs-purchased-licenses-admin',
				'eddShowActiveVsPurchasedLicenses',
				apply_filters( 'edd_show_active_vs_purchased_licenses_localize_admin_script', array(
					'ajaxURL' => admin_url( 'admin-ajax.php' ),
				) )
			);
			
		}
		
	}
	
} // End Class Exists Check

/**
 * The main function responsible for returning the one true EDD_Show_Active_vs_Purchased_Licenses
 * instance to functions everywhere
 *
 * @since	  {{VERSION}}
 * @return	  \EDD_Show_Active_vs_Purchased_Licenses The one true EDD_Show_Active_vs_Purchased_Licenses
 */
add_action( 'plugins_loaded', 'EDD_Show_Active_vs_Purchased_Licenses_load' );
function EDD_Show_Active_vs_Purchased_Licenses_load() {

	require_once __DIR__ . '/core/edd-show-active-vs-purchased-licenses-functions.php';
	EDDSHOWACTIVEVSPURCHASEDLICENSES();

}