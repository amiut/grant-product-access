<?php
/**
 * bankID main class
 *
 * @package DW_GACCESS/AutoLoader
 * @since   1.0
 */

namespace DW_GACCESS;

defined('ABSPATH') || exit;

if (version_compare(PHP_VERSION, '5.6.0', '<')) {
    throw new \Exception('PHP newer than 5.6 is required');
}

class Auto_Loader
{
	/**
	 * Path to the includes directory.
	 *
	 * @var string
	 */
    private $include_path = '';

    /**
     * Files to exclude
     *
     * @var array
     */
    protected $exluded_files;

	/**
	 * The Constructor.
	 */
	public function __construct() {
        $this->composer();

		if ( function_exists( '__autoload' ) ) {
			spl_autoload_register( '__autoload' );
        }

        spl_autoload_register( array( $this, 'autoload' ) );

        $this->include_path = untrailingslashit( plugin_dir_path( DW_GACCESS_FILE ) ) . '/includes/';

		$this->exluded_files = [
			// $this->include_path . 'class-example.php',
		];
    }

    /**
     * Include composer auto loader
     */
    public function composer() {
        if (file_exists($this->include_path . 'vendor/autoload.php')) {
            include $this->include_path . 'vendor/autoload.php';
        }
    }

	/**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class Class name.
	 * @return string
	 */
	protected function get_file_name_from_class( $class ) {
        $prefix = 'DW_GACCESS\\';

        // Does the class use the namespace prefix?
        $len = strlen( $prefix );
        if ( 0 !== strncmp( $prefix, $class, $len ) ) {
            // No, move to the next registered autoloader.
            return false;
        }


        // Get the relative class name.
        $relative_class = substr( $class, $len );

        $path = explode( '\\', strtolower( str_replace( '_', '-', $relative_class ) ) );
        $file = array_pop( $path );
        $filepath = implode("/", $path) . "/class-{$file}.php";
        return str_replace("//", "/", $filepath);
    }

	/**
	 * Include a class file.
	 *
	 * @param  string $path File path.
	 * @return bool Successful or not.
	 */
	private function load_file( $path ) {
        if ( $path && is_readable( $path ) && ! in_array($path, $this->exluded_files) ) {
			require_once $path;
			return true;
		}

		return false;
    }

    /**
	 * Auto-load plugin classes on demand to reduce memory consumption.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class ) {
        $file = $this->get_file_name_from_class( $class );

        if (! $file) {
            return; // Skip the autoloader
        }


		$path = '';
		if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {
			$this->load_file( $this->include_path . $file );
		}
	}
}

return new Auto_Loader();
