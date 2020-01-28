<?php
/*
* Plugin Name: Ajánlat Kalkulátor
* Version: 1.0
* Plugin URI: https://www.cegautoberlet.hu/
* Description: Ajánlat generálás a megadott adatok alapján.
* Author: Honlapvarázsló
* Author URI: https://honlapvarazslo.com
* Requires at least: 4.0
* Tested up to: 5.2.1
*
* Text Domain: cegauto-kalkulator
* Domain Path: /lang/
*
* @package WordPress
* @author Richard Szegh
* @since 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Load plugin class files
require_once( 'includes/class-cegauto-kalkulator.php' );
require_once( 'includes/class-cegauto-kalkulator-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-cegauto-kalkulator-admin-api.php' );
require_once( 'includes/lib/class-cegauto-kalkulator-post-type.php' );

// Load shortcode
require_once( 'includes/cegauto-kalkulator-shortcode.php' );

/**
 * Returns the main instance of CegautoKalkulator to prevent the need to use globals.
 *
 * @return object CegautoKalkulator
 * @since  1.0.0
 */
function CegautoKalkulator() {
  $instance = CegautoKalkulator::instance( __FILE__, '1.0.1' );

  if ( is_null( $instance->settings ) ) {
    $instance->settings = CegautoKalkulator_Settings::instance( $instance );
  }

  return $instance;
}

CegautoKalkulator();
