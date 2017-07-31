<?php
/**
 * Handles the dependencies and enqueueing of the CMB2 JS scripts
 *
 * @category  WordPress_Plugin
 * @package   CMB2
 * @author    WebDevStudios
 * @license   GPL-2.0+
 * @link      http://webdevstudios.com
 */
class CMB2_JS {

	/**
	 * The CMB2 JS handle
	 * @var   string
	 * @since 2.0.7
	 */
	protected static $handle = 'cmb2-scripts';

	/**
	 * The CMB2 JS variable name
	 * @var   string
	 * @since 2.0.7
	 */
	protected static $js_variable = 'cmb2_l10';

	/**
	 * Array of CMB2 JS dependencies
	 * @var   array
	 * @since 2.0.7
	 */
	protected static $dependencies = array( 'jquery' => 'jquery' );

	/**
	 * Add a dependency to the array of CMB2 JS dependencies
	 * @since 2.0.7
	 * @param array|string  $dependencies Array (or string) of dependencies to add
	 */
	public static function add_dependencies( $dependencies ) {
		foreach ( (array) $dependencies as $dependency ) {
			self::$dependencies[ $dependency ] = $dependency;
		}
	}

	/**
	 * Enqueue the CMB2 JS
	 * @since  2.0.7
	 */
	public static function enqueue() {
		// Filter required script dependencies
		$dependencies = apply_filters( 'cmb2_script_dependencies', self::$dependencies );

		// Only use minified files if SCRIPT_DEBUG is off
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$min = $debug ? '' : '.min';

		// if colorpicker
		if ( ! is_admin() && isset( $dependencies['wp-color-picker'] ) ) {
			self::colorpicker_frontend();
		}

		// if file/file_list
		if ( isset( $dependencies['media-editor'] ) ) {
			wp_enqueue_media();
		}

		// if timepicker
		if ( isset( $dependencies['jquery-ui-datetimepicker'] ) ) {
			wp_register_script( 'jquery-ui-datetimepicker', CMB2_Utils::url( 'js/jquery-ui-timepicker-addon.min.js' ), array( 'jquery-ui-slider' ), CMB2_VERSION );
		}

		// if cmb2-wysiwyg
		$enqueue_wysiwyg = isset( $dependencies['cmb2-wysiwyg'] ) && $debug;
		unset( $dependencies['cmb2-wysiwyg'] );

		// Enqueue cmb JS
		wp_enqueue_script( self::$handle, CMB2_Utils::url( "js/cmb2{$min}.js" ), $dependencies, CMB2_VERSION, true );

		// if SCRIPT_DEBUG, we need to enqueue separately.
		if ( $enqueue_wysiwyg ) {
			wp_enqueue_script( 'cmb2-wysiwyg', CMB2_Utils::url( 'js/cmb2-wysiwyg.js' ), array( 'jquery', 'wp-util' ), CMB2_VERSION );
		}

		self::localize( $debug );
	}

	/**
	 * We need to register colorpicker on the front-end
	 * @since  2.0.7
	 */
	protected static function colorpicker_frontend() {
		wp_register_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), CMB2_VERSION );
		wp_register_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), CMB2_VERSION );
		wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', array(
			'clear'         => esc_html__( 'Clear', 'distinctpress-pro' ),
			'defaultString' => esc_html__( 'Default', 'distinctpress-pro' ),
			'pick'          => esc_html__( 'Select Color', 'distinctpress-pro' ),
			'current'       => esc_html__( 'Current Color', 'distinctpress-pro' ),
		) );
	}

	/**
	 * Localize the php variables for CMB2 JS
	 * @since  2.0.7
	 */
	protected static function localize( $debug ) {
		static $localized = false;
		if ( $localized ) {
			return;
		}

		$localized = true;
		$l10n = array(
			'ajax_nonce'       => wp_create_nonce( 'ajax_nonce' ),
			'ajaxurl'          => admin_url( '/admin-ajax.php' ),
			'script_debug'     => $debug,
			'up_arrow_class'   => 'dashicons dashicons-arrow-up-alt2',
			'down_arrow_class' => 'dashicons dashicons-arrow-down-alt2',
			'defaults'         => array(
				'color_picker' => false,
				'date_picker'  => array(
					'changeMonth'     => true,
					'changeYear'      => true,
					'dateFormat'      => _x( 'mm/dd/yy', 'Valid formatDate string for jquery-ui datepicker', 'distinctpress-pro' ),
					'dayNames'        => explode( ',', esc_html__( 'Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday', 'distinctpress-pro' ) ),
					'dayNamesMin'     => explode( ',', esc_html__( 'Su, Mo, Tu, We, Th, Fr, Sa', 'distinctpress-pro' ) ),
					'dayNamesShort'   => explode( ',', esc_html__( 'Sun, Mon, Tue, Wed, Thu, Fri, Sat', 'distinctpress-pro' ) ),
					'monthNames'      => explode( ',', esc_html__( 'January, February, March, April, May, June, July, August, September, October, November, December', 'distinctpress-pro' ) ),
					'monthNamesShort' => explode( ',', esc_html__( 'Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec', 'distinctpress-pro' ) ),
					'nextText'        => esc_html__( 'Next', 'distinctpress-pro' ),
					'prevText'        => esc_html__( 'Prev', 'distinctpress-pro' ),
					'currentText'     => esc_html__( 'Today', 'distinctpress-pro' ),
					'closeText'       => esc_html__( 'Done', 'distinctpress-pro' ),
					'clearText'       => esc_html__( 'Clear', 'distinctpress-pro' ),
				),
				'time_picker'  => array(
					'timeOnlyTitle' => esc_html__( 'Choose Time', 'distinctpress-pro' ),
					'timeText'      => esc_html__( 'Time', 'distinctpress-pro' ),
					'hourText'      => esc_html__( 'Hour', 'distinctpress-pro' ),
					'minuteText'    => esc_html__( 'Minute', 'distinctpress-pro' ),
					'secondText'    => esc_html__( 'Second', 'distinctpress-pro' ),
					'currentText'   => esc_html__( 'Now', 'distinctpress-pro' ),
					'closeText'     => esc_html__( 'Done', 'distinctpress-pro' ),
					'timeFormat'    => _x( 'hh:mm TT', 'Valid formatting string, as per http://trentrichardson.com/examples/timepicker/', 'distinctpress-pro' ),
					'controlType'   => 'select',
					'stepMinute'    => 5,
				),
			),
			'strings' => array(
				'upload_file'  => esc_html__( 'Use this file', 'distinctpress-pro' ),
				'upload_files' => esc_html__( 'Use these files', 'distinctpress-pro' ),
				'remove_image' => esc_html__( 'Remove Image', 'distinctpress-pro' ),
				'remove_file'  => esc_html__( 'Remove', 'distinctpress-pro' ),
				'file'         => esc_html__( 'File:', 'distinctpress-pro' ),
				'download'     => esc_html__( 'Download', 'distinctpress-pro' ),
				'check_toggle' => esc_html__( 'Select / Deselect All', 'distinctpress-pro' ),
			),
		);

		wp_localize_script( self::$handle, self::$js_variable, apply_filters( 'cmb2_localized_data', $l10n ) );
	}

}