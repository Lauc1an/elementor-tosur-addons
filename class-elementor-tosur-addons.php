<?php
/**
 * Elementor_Tosur_Addons class.
 *
 * @category   Class
 * @package    ElementorTosurAddons
 * @subpackage WordPress
 * @author     Laucian <alexander@aerastudio.pe>
 * @copyright  2022 Laucian
 * @license    https://opensource.org/licenses/GPL-3.0 GPL-3.0-only
 * @link       link(https://github.com/Lauc1an/elementor-tosur-addons,
 *             Elementor Widgets for Tosur Product Forms)
 * @since      1.0.0
 * php version 7.4.26
 */

if ( ! defined( 'ABSPATH' ) ) {
	// Exit if accessed directly.
	exit;
}

/**
 * Main Elementor Tosur Addons Class
 *
 * The init class that runs the Elementor Tosur Addons plugin.
 * Intended To make sure that the plugin's minimum requirements are met.
 *
 * You should only modify the constants to match your plugin's needs.
 *
 * Any custom code should go inside Plugin Class in the class-widgets.php file.
 */
final class Elementor_Tosur_Addons {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */

	const MINIMUM_PHP_VERSION = '7.3';

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		// Load the translation.
		add_action( 'init', array( $this, 'i18n' ) );
		// Initialize the plugin.
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'elementor-tosur-addons' );
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}
		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}
		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}
		// Once we get here, We have passed all validation checks so we can safely include our widgets.
		require_once 'class-widgets.php';
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {
		deactivate_plugins( plugin_basename( ELEMENTOR_TOSUR_ADDONS ) );
		return sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> to be installed and activated.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Elementor Tosur Addons',
			'Elementor'
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {
		deactivate_plugins( plugin_basename( ELEMENTOR_TOSUR_ADDONS ) );
		return sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Elementor Tosur Addons',
			'Elementor',
			self::MINIMUM_ELEMENTOR_VERSION
		);
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {
		deactivate_plugins( plugin_basename( ELEMENTOR_TOSUR_ADDONS ) );
		return sprintf(
			wp_kses(
				'<div class="notice notice-warning is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
				array(
					'div' => array(
						'class'  => array(),
						'p'      => array(),
						'strong' => array(),
					),
				)
			),
			'Elementor Tosur Addons',
			'Elementor',
			self::MINIMUM_ELEMENTOR_VERSION
		);
	}
}

// Instantiate Elementor_Tosur_Addons.
new Elementor_Tosur_Addons();
