<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 *
 * @link https://mysterythemes.com
 * @since 1.0.0
 *
 * @package Mystery Themes Demo Importer
 * @subpackage /includes/wp-importers/customizer-class
 * 
 */

/**
 * Customizer Demo Importer Setting class.
 *
 * @see WP_Customize_Setting
 */
final class MTDI_Customizer_Setting extends WP_Customize_Setting {

	/**
	 * Import an option value for this setting.
	 *
	 * @param mixed $value The value to update.
	 */
	public function import( $value ) {
		$this->update( $value );
	}
}
