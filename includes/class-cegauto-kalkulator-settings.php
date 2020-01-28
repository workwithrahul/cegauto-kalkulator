<?php
/** @noinspection PhpUndefinedFunctionInspection */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class CegautoKalkulator_Settings {

  /**
   * The single instance of CegautoKalkulator_Settings.
   * @var  object
   * @access  private
   * @since  1.0.0
   */
  private static $_instance = null;

  /**
   * The main plugin object.
   * @var  object
   * @access  public
   * @since  1.0.0
   */
  public $parent = null;

  /**
   * Prefix for plugin settings.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $base = '';

  /**
   * Available settings for plugin.
   * @var     array
   * @access  public
   * @since   1.0.0
   */
  public $settings = array();

  public function __construct( $parent ) {
    $this->parent = $parent;

    $this->base = 'cak_';

    // Initialise settings
    add_action( 'init', array( $this, 'init_settings' ), 11 );

    // Register plugin settings
    add_action( 'admin_init', array( $this, 'register_settings' ) );

    // Add settings page to menu
    add_action( 'admin_menu', array( $this, 'add_menu_item' ) );

    // Add settings link to plugins page
    add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ), array( $this, 'add_settings_link' ) );
  }

  /**
   * Main CegautoKalkulator_Settings Instance
   *
   * Ensures only one instance of CegautoKalkulator_Settings is loaded or can be loaded.
   *
   * @return Main CegautoKalkulator_Settings instance
   * @see CegautoKalkulator()
   * @since 1.0.0
   * @static
   */
  public static function instance( $parent ) {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self( $parent );
    }

    return self::$_instance;
  }

  /**
   * Initialise settings
   * @return void
   */
  public function init_settings() {
    $this->settings = $this->settings_fields();
  }

  /**
   * Build settings fields
   * @return array Fields to be displayed on settings page
   */
  private function settings_fields() {

    $settings['altalanos'] = array(
      'title'       => __( 'Általános', 'cegauto-kalkulator' ),
      'description' => __( 'Általános beállítások.', 'cegauto-kalkulator' ),
      'fields'      => array(
        //ALTALANOS BEALLATISOK
        array(
          'id'          => 'kamat_field',
          'label'       => __( 'Kamat', 'cegauto-kalkulator' ),
          'description' => __( 'A kamat százalékban.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'step'        => 'any',
          'default'     => '7.50',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'casco_field',
          'label'       => __( 'Casco szorzó', 'cegauto-kalkulator' ),
          'description' => __( 'A Casco szorzó százalékban.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'step'        => 'any',
          'default'     => '2.50',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'torzs_forgalmi_rendszam_vizsgacimke_field',
          'label'       => __( 'Törzskönyv, forgalmi engedély, rendszám, vizsgacímke', 'cegauto-kalkulator' ),
          'description' => __( 'Törzskönyv, forgalmi engedély, rendszám, vizsgacímke ára Forintban.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '20500',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'uzembehelyezes_field',
          'label'       => __( 'Üzembe helyezés költsége', 'cegauto-kalkulator' ),
          'description' => __( 'Üzembe helyezés költsége Forintban (0 revizió / GPS / Rablásgátló stb.).', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '100000',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'flottadij_field',
          'label'       => __( 'Flotta díj', 'cegauto-kalkulator' ),
          'description' => __( 'Flotta díj Forintban.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '2500',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
        ),
        //DARABSZAM BEALLITASOK
        //regado field count
        array(
          'id'          => 'regado_db',
          'label'       => __( 'Regisztrációs adó mező darabszám', 'cegauto-kalkulator' ),
          'description' => __( 'Regisztrációs mező darabszám.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '0',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        ),
        //cegautoado field count
        array(
          'id'          => 'cegautoado_db',
          'label'       => __( 'Cégautó adó mező darabszám', 'cegauto-kalkulator' ),
          'description' => __( 'Cégautó adó mező darabszám.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '0',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        ),
        //kotelezobiztositas field count
        array(
          'id'          => 'kotelezobiztositas_db',
          'label'       => __( 'Kötelező biztosítás mező darabszám', 'cegauto-kalkulator' ),
          'description' => __( 'Kötelező biztosítás mező darabszám.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '0',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        ),
        //vagyonszerzesiilletek field count
        array(
          'id'          => 'vagyonszerzesiilletek_kw_db',
          'label'       => __( 'Vagyonszerzési illeték mező darabszám', 'cegauto-kalkulator' ),
          'description' => __( 'Vagyonszerzési illeték mező darabszám.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'default'     => '0',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        ),
      ),
    );

    //generates regado fields
    $regado_field_count = get_option( 'cak_regado_db' );
    $regado_fields      = array();
    for ( $i = 1; $i <= $regado_field_count; $i++ ) {
      //min kw
      array_push(
        $regado_fields,
        array(
          'id'          => 'regado_kw_min_' . $i,
          'label'       => __( $i . '. Min. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Minimum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //max kw
      array_push(
        $regado_fields,
        array(
          'id'          => 'regado_kw_max_' . $i,
          'label'       => __( $i . '. Max. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Maximum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //ar
      array_push(
        $regado_fields,
        array(
          'id'          => 'regado_kw_ar_' . $i,
          'label'       => __( $i . '. Forint/hó', 'cegauto-kalkulator' ),
          'description' => __( 'Forint', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
    }

    //generates cegautoado fields
    $cegautoado_field_count = get_option( 'cak_cegautoado_db' );
    $cegautoado_fields      = array();
    for ( $i = 1; $i <= $cegautoado_field_count; $i++ ) {
      //min kw
      array_push(
        $cegautoado_fields,
        array(
          'id'          => 'cegautoado_kw_min_' . $i,
          'label'       => __( $i . '. Min. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Minimum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //max kw
      array_push(
        $cegautoado_fields,
        array(
          'id'          => 'cegautoado_kw_max_' . $i,
          'label'       => __( $i . '. Max. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Maximum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //ar
      array_push(
        $cegautoado_fields,
        array(
          'id'          => 'cegautoado_kw_ar_' . $i,
          'label'       => __( $i . '. Forint/hó', 'cegauto-kalkulator' ),
          'description' => __( 'Forint/hó', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
    }

    //generates kotelezobiztositas fields
    $kotelezobiztositas_field_count = get_option( 'cak_kotelezobiztositas_db' );
    $kotelezobiztositas_fields      = array();
    for ( $i = 1; $i <= $kotelezobiztositas_field_count; $i++ ) {
      //min kw
      array_push(
        $kotelezobiztositas_fields,
        array(
          'id'          => 'kotelezobiztositas_kw_min_' . $i,
          'label'       => __( $i . '. Min. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Minimum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //max kw
      array_push(
        $kotelezobiztositas_fields,
        array(
          'id'          => 'kotelezobiztositas_kw_max_' . $i,
          'label'       => __( $i . '. Max. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Maximum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //ar
      array_push(
        $kotelezobiztositas_fields,
        array(
          'id'          => 'kotelezobiztositas_kw_ar_' . $i,
          'label'       => __( $i . '. Forint/hó', 'cegauto-kalkulator' ),
          'description' => __( 'Forint/hó', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
    }

    //generates vagyonszerzesiilletek fields
    $vagyonszerzesiilletek_field_count = get_option( 'cak_vagyonszerzesiilletek_kw_db' );
    $vagyonszerzesiilletek_fields      = array();
    for ( $i = 1; $i <= $vagyonszerzesiilletek_field_count; $i++ ) {
      //min kw
      array_push(
        $vagyonszerzesiilletek_fields,
        array(
          'id'          => 'vagyonszerzesiilletek_kw_min_' . $i,
          'label'       => __( $i . '. Min. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Minimum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //max kw
      array_push(
        $vagyonszerzesiilletek_fields,
        array(
          'id'          => 'vagyonszerzesiilletek_kw_max_' . $i,
          'label'       => __( $i . '. Max. kw', 'cegauto-kalkulator' ),
          'description' => __( 'Maximum kilowatt.', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
      //ar
      array_push(
        $vagyonszerzesiilletek_fields,
        array(
          'id'          => 'vagyonszerzesiilletek_kw_ar_' . $i,
          'label'       => __( $i . '. Forint', 'cegauto-kalkulator' ),
          'description' => __( 'Forint', 'cegauto-kalkulator' ),
          'type'        => 'number',
          'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' ),
        )
      );
    }

    //generates ME fields;
    $maradvagyertek_cols = array( 10000, 20000, 30000, 40000, 50000, 60000 );
    $maradvanyertek_rows = array(
      array(
        'min' => '0',
        'max' => '5499999',
      ),
      array(
        'min' => '5500000',
        'max' => '7999999',
      ),
      array(
        'min' => '8000000',
        'max' => '11499000',
      ),
      array(
        'min' => '11500000',
        'max' => '17999999',
      ),
      array(
        'min' => '18000000',
        'max' => '28999999',
      ),
      array(
        'min' => '29000000',
        'max' => '35999999',
      ),
      array(
        'min' => '36000000',
        'max' => ''
      )
    );
    $me_cols_count       = sizeof( $maradvagyertek_cols );
    $me_rows_count       = sizeof( $maradvanyertek_rows );

    //year 1
    $maradvanyertek_fields_yr1 = array();
    for ( $i = 0; $i < $me_cols_count; $i++ ) {
      for ( $j = 0; $j < $me_rows_count; $j++ ) {
        //generate cols * rows fields for year 1
        array_push(
          $maradvanyertek_fields_yr1,
          array(
            'id'          => 'me_yr1_val_' . $i . '_' . $j,
            'label'       => __( $maradvagyertek_cols[ $i ] .
                                 ' - [' . $maradvanyertek_rows[ $j ]['min'] .
                                 '-' .
                                 $maradvanyertek_rows[ $j ]['max'] .
                                 ']', 'cegauto-kalkulator' ),
            'description' => __( 'Százalék', 'cegauto-kalkulator' ),
            'type'        => 'number',
            'step'        => 'any',
            'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
          )
        );
      }
    }

    //year 2
    $maradvanyertek_fields_yr2 = array();
    for ( $i = 0; $i < $me_cols_count; $i++ ) {
      for ( $j = 0; $j < $me_rows_count; $j++ ) {
        //generate cols * rows fields for year 2
        array_push(
          $maradvanyertek_fields_yr2,
          array(
            'id'          => 'me_yr2_val_' . $i . '_' . $j,
            'label'       => __( $maradvagyertek_cols[ $i ] .
                                 ' - [' . $maradvanyertek_rows[ $j ]['min'] .
                                 '-' .
                                 $maradvanyertek_rows[ $j ]['max'] .
                                 ']', 'cegauto-kalkulator' ),
            'description' => __( 'Százalék', 'cegauto-kalkulator' ),
            'type'        => 'number',
            'step'        => 'any',
            'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
          )
        );
      }
    }

    //year 3
    $maradvanyertek_fields_yr3 = array();
    for ( $i = 0; $i < $me_cols_count; $i++ ) {
      for ( $j = 0; $j < $me_rows_count; $j++ ) {
        //generate cols * rows fields for year 3
        array_push(
          $maradvanyertek_fields_yr3,
          array(
            'id'          => 'me_yr3_val_' . $i . '_' . $j,
            'label'       => __( $maradvagyertek_cols[ $i ] .
                                 ' - [' . $maradvanyertek_rows[ $j ]['min'] .
                                 '-' .
                                 $maradvanyertek_rows[ $j ]['max'] .
                                 ']', 'cegauto-kalkulator' ),
            'description' => __( 'Százalék', 'cegauto-kalkulator' ),
            'type'        => 'number',
            'step'        => 'any',
            'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
          )
        );
      }
    }

    //year 4
    $maradvanyertek_fields_yr4 = array();
    for ( $i = 0; $i < $me_cols_count; $i++ ) {
      for ( $j = 0; $j < $me_rows_count; $j++ ) {
        //generate cols * rows fields for year 4
        array_push(
          $maradvanyertek_fields_yr4,
          array(
            'id'          => 'me_yr4_val_' . $i . '_' . $j,
            'label'       => __( $maradvagyertek_cols[ $i ] .
                                 ' - [' . $maradvanyertek_rows[ $j ]['min'] .
                                 '-' .
                                 $maradvanyertek_rows[ $j ]['max'] .
                                 ']', 'cegauto-kalkulator' ),
            'description' => __( 'Százalék', 'cegauto-kalkulator' ),
            'type'        => 'number',
            'step'        => 'any',
            'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
          )
        );
      }
    }

    //year 5
    $maradvanyertek_fields_yr5 = array();
    for ( $i = 0; $i < $me_cols_count; $i++ ) {
      for ( $j = 0; $j < $me_rows_count; $j++ ) {
        //generate cols * rows fields for year 5
        array_push(
          $maradvanyertek_fields_yr5,
          array(
            'id'          => 'me_yr5_val_' . $i . '_' . $j,
            'label'       => __( $maradvagyertek_cols[ $i ] .
                                 ' - [' . $maradvanyertek_rows[ $j ]['min'] .
                                 '-' .
                                 $maradvanyertek_rows[ $j ]['max'] .
                                 ']', 'cegauto-kalkulator' ),
            'description' => __( 'Százalék', 'cegauto-kalkulator' ),
            'type'        => 'number',
            'step'        => 'any',
            'placeholder' => __( 'Adjon meg egy értéket!', 'cegauto-kalkulator' )
          )
        );
      }
    }

    $settings['regado'] = array(
      'title'       => __( 'Regisztrációs adó', 'cegauto-kalkulator' ),
      'description' => __( 'Regisztrációs adó beállítások.', 'cegauto-kalkulator' ),
      'fields'      => $regado_fields,
    );

    $settings['cegautoado'] = array(
      'title'       => __( 'Cégautó adó', 'cegauto-kalkulator' ),
      'description' => __( 'Cégautó adó beállítások.', 'cegauto-kalkulator' ),
      'fields'      => $cegautoado_fields,
    );

    $settings['kotelezobiztositas'] = array(
      'title'       => __( 'Kötelező biztosítás', 'cegauto-kalkulator' ),
      'description' => __( 'Kötelező biztosítás beállítások.', 'cegauto-kalkulator' ),
      'fields'      => $kotelezobiztositas_fields,
    );

    $settings['vagyonszerzesiilletek'] = array(
      'title'       => __( 'Vagyonszerzési illeték', 'cegauto-kalkulator' ),
      'description' => __( 'Vagyonszerzési illeték beállítások.', 'cegauto-kalkulator' ),
      'fields'      => $vagyonszerzesiilletek_fields,
    );

    $settings['maradvanyertek_yr1'] = array(
      'title'       => __( 'MÉ (12 hónap)', 'cegauto-kalkulator' ),
      'description' => __( 'Maradványérték százalékok 12 hónapos futamidőkhöz. (FUTAMIDŐ - [MIN.VÉTELÁR-MAX. VÉTELÁR])', 'cegauto-kalkulator' ),
      'fields'      => $maradvanyertek_fields_yr1,
    );

    $settings['maradvanyertek_yr2'] = array(
      'title'       => __( 'MÉ (24 hónap)', 'cegauto-kalkulator' ),
      'description' => __( 'Maradványérték százalékok 24 hónapos futamidőkhöz. (FUTAMIDŐ - [MIN.VÉTELÁR-MAX. VÉTELÁR])', 'cegauto-kalkulator' ),
      'fields'      => $maradvanyertek_fields_yr2,
    );

    $settings['maradvanyertek_yr3'] = array(
      'title'       => __( 'MÉ (36 hónap)', 'cegauto-kalkulator' ),
      'description' => __( 'Maradványérték százalékok 36 hónapos futamidőkhöz. (FUTAMIDŐ - [MIN.VÉTELÁR-MAX. VÉTELÁR])', 'cegauto-kalkulator' ),
      'fields'      => $maradvanyertek_fields_yr3,
    );

    $settings['maradvanyertek_yr4'] = array(
      'title'       => __( 'MÉ (48 hónap)', 'cegauto-kalkulator' ),
      'description' => __( 'Maradványérték százalékok 48 hónapos futamidőkhöz. (FUTAMIDŐ - [MIN.VÉTELÁR-MAX. VÉTELÁR])', 'cegauto-kalkulator' ),
      'fields'      => $maradvanyertek_fields_yr4,
    );

    $settings['maradvanyertek_yr5'] = array(
      'title'       => __( 'MÉ (60 hónap)', 'cegauto-kalkulator' ),
      'description' => __( 'Maradványérték százalékok 60 hónapos futamidőkhöz. (FUTAMIDŐ - [MIN.VÉTELÁR-MAX. VÉTELÁR])', 'cegauto-kalkulator' ),
      'fields'      => $maradvanyertek_fields_yr5,
    );

    $settings['api'] = array(
      'title'       => __( 'API', 'cegauto-kalkulator' ),
      'description' => __( 'API beállítások.', 'cegauto-kalkulator' ),
      'fields'      => array(
        array(
          'id'          => 'url',
          'label'       => __( 'SalesAutoPilot URL', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( 'Adja meg az URL-t.', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'username',
          'label'       => __( 'SalesAutoPilot Felhasználónév', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( '', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'password',
          'label'       => __( 'SalesAutoPilot Jelszó', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'password',
          'placeholder' => __( '', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'tyurl',
          'label'       => __( 'Sikeres feliratkozás átirányítás', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( '', 'cegauto-kalkulator' )
        )
      ),
    );
    $settings['minicrm'] = array(
      'title'       => __( 'Mini CRM', 'cegauto-kalkulator' ),
      'description' => __( '', 'cegauto-kalkulator' ),
      'fields'      => array(
        array(
          'id'          => 'mini_crm_url',
          'label'       => __( 'API URL', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( 'API URL', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'api_key',
          'label'       => __( 'API KEY', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( 'API KEY', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'system_id',
          'label'       => __( 'System ID', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( 'System id', 'cegauto-kalkulator' )
        ),
        array(
          'id'          => 'mini_tyurl',
          'label'       => __( 'Sikeres feliratkozás átirányítás', 'cegauto-kalkulator' ),
          'description' => __( '', 'cegauto-kalkulator' ),
          'type'        => 'text',
          'placeholder' => __( '', 'cegauto-kalkulator' )
        )
      ),
    );
    $settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

    return $settings;
  }

  /**
   * Add settings page to admin menu
   * @return void
   */
  public function add_menu_item() {
    $page = add_menu_page(
      __( 'Ajánlat Kalkulátor beállítások', 'cegauto-kalkulator' ),
      __( 'Ajánlat Kalkulátor', 'cegauto-kalkulator' ),
      'manage_options',
      $this->parent->_token . '_settings',
      array( $this, 'settings_page' ),
      'dashicons-forms',
      65
    );
    add_action( 'admin_print_styles-' . $page, array( $this, 'settings_assets' ) );
  }

  /**
   * Load settings JS & CSS
   * @return void
   */
  public function settings_assets() {

    // We're including the WP media scripts here because they're needed for the image upload field
    // If you're not including an image upload then you can leave this function call out
    wp_enqueue_media();

    wp_register_script( $this->parent->_token . '-settings-js', $this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js', array(
      'jquery'
    ), '1.0.0' );
    wp_enqueue_script( $this->parent->_token . '-settings-js' );
  }

  /**
   * Add settings link to plugin list table
   *
   * @param array $links Existing links
   *
   * @return array    Modified links
   */
  public function add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=' . $this->parent->_token . '_settings">' . __( 'Beállítások', 'cegauto-kalkulator' ) . '</a>';
    array_push( $links, $settings_link );

    return $links;
  }

  /**
   * Register plugin settings
   * @return void
   */
  public function register_settings() {
    if ( is_array( $this->settings ) ) {

      // Check posted/selected tab
      $current_section = '';
      if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
        $current_section = $_POST['tab'];
      } else {
        if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
          $current_section = $_GET['tab'];
        }
      }

      foreach ( $this->settings as $section => $data ) {

        if ( $current_section && $current_section != $section ) {
          continue;
        }

        // Add section to page
        add_settings_section( $section, $data['title'], array(
          $this,
          'settings_section'
        ), $this->parent->_token . '_settings' );

        foreach ( $data['fields'] as $field ) {

          // Validation callback for field
          $validation = '';
          if ( isset( $field['callback'] ) ) {
            $validation = $field['callback'];
          }

          // Register field
          $option_name = $this->base . $field['id'];
          register_setting( $this->parent->_token . '_settings', $option_name, $validation );

          // Add field to page
          add_settings_field(
            $field['id'],
            $field['label'],
            array(
              $this->parent->admin,
              'display_field'
            ),
            $this->parent->_token . '_settings',
            $section,
            array(
              'field'  => $field,
              'prefix' => $this->base
            )
          );
        }

        if ( ! $current_section ) {
          break;
        }
      }
    }
  }

  public function settings_section( $section ) {
    $html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
    echo $html;
  }

  /**
   * Load settings page content
   * @return void
   */
  public function settings_page() {

    // Build page HTML
    $html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
    $html .= '<h2>' . __( 'Ajánlat Kalkulátor', 'cegauto-kalkulator' ) . '</h2>' . "\n";

    $tab = '';
    if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
      $tab .= $_GET['tab'];
    }

    // Show page tabs
    if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

      $html .= '<h2 class="nav-tab-wrapper">' . "\n";

      $c = 0;
      foreach ( $this->settings as $section => $data ) {

        // Set tab class
        $class = 'nav-tab';
        if ( ! isset( $_GET['tab'] ) ) {
          if ( 0 == $c ) {
            $class .= ' nav-tab-active';
          }
        } else {
          if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
            $class .= ' nav-tab-active';
          }
        }

        // Set tab link
        $tab_link = add_query_arg( array( 'tab' => $section ) );
        if ( isset( $_GET['settings-updated'] ) ) {
          $tab_link = remove_query_arg( 'settings-updated', $tab_link );
        }

        // Output tab
        $html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

        ++$c;
      }

      $html .= '</h2>' . "\n";
    }

    $html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

    // Get settings fields
    ob_start();
    settings_fields( $this->parent->_token . '_settings' );
    do_settings_sections( $this->parent->_token . '_settings' );
    $html .= ob_get_clean();

    $html .= '<p class="submit">' . "\n";
    $html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
    $html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Beállítások elmentése', 'cegauto-kalkulator' ) ) . '" />' . "\n";
    $html .= '</p>' . "\n";
    $html .= '</form>' . "\n";
    $html .= '</div>' . "\n";

    echo $html;
  } // End instance()

  /**
   * Cloning is forbidden.
   *
   * @since 1.0.0
   */
  public function __clone() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
  } // End __clone()

  /**
   * Unserializing instances of this class is forbidden.
   *
   * @since 1.0.0
   */
  public function __wakeup() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
  } // End __wakeup()

}
