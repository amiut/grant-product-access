<?php
/**
 * bankID main class
 *
 * @package DW_GACCESSS
 * @since   1.0
 */

namespace DW_GACCESS;

defined('ABSPATH') || exit;

/**
 * DW_GACCESSS main class
 */
final class App
{
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
    public $version = '1.0';

    /**
     * Plugin instance.
     *
     * @since 1.0
     * @var null|DW_GACCESSS
     */
    public static $instance = null;

    /**
     * Plugin API.
     *
     * @since 1.0
     * @var DW_GACCESSS\API\API
     */
    public $api = '';


    /**
     * Return the plugin instance.
     *
     * @return Dornaweb_Pack
     */
    public static function instance() {
        if ( ! self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Dornaweb_Pack constructor.
     */
    private function __construct() {
        $this->define_constants();

        $this->init();
        $this->includes();

        add_action('admin_init', [$this, 'admin_notices']);
    }

    /**
     * Include required files
     *
     */
    public function includes() {
        include DW_GACCESS_ABSPATH . 'includes/functions.php';
    }

    /**
     * Define constant if not already set.
     *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
    }

    /**
     * Define constants
     */
    public function define_constants() {

		$this->define('DW_GACCESS_ABSPATH', dirname(DW_GACCESS_FILE) . '/');
		$this->define('DW_GACCESS_PLUGIN_BASENAME', plugin_basename(DW_GACCESS_FILE));
		$this->define('DW_GACCESS_BOOKING_VERSION', $this->version);
		$this->define('DW_GACCESS_PLUGIN_URL', $this->plugin_url());
		$this->define('DW_GACCESS_API_TEST_MODE', true);
    }

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit(plugins_url('/', DW_GACCESS_FILE));
    }

    /**
     * Do initial stuff
     */
    public function init() {
        // Add scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'public_dependencies']);

        Admin\Admin_Controller::init();
    }

    /**
     * Register scripts and styles for public area
     */
    public function public_dependencies()
    {
    }

    /**
     * Add admin notices
     */
    public function admin_notices()
    {
        if (! $this->wc_enabled()) {
            add_action('admin_notices', [$this, 'wc_required_notice'], 1);
        }
    }

    /**
     * Check if Woocommerce is enabled
     */
    public function wc_enabled() {
        return function_exists('WC');
    }

    /**
     * Woocommerce Required notice message
     */
    public function wc_required_notice()
    {
        echo '<div class="notice notice-error">
        <p>این افزونه بدون ووکامرس کار نمیکند</p>
       </div>';
    }
}
