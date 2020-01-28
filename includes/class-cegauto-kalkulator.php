<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class CegautoKalkulator {

  /**
   * The single instance of CegautoKalkulator.
   * @var  object
   * @access  private
   * @since  1.0.0
   */
  private static $_instance = null;

  /**
   * Settings class object
   * @var     object
   * @access  public
   * @since   1.0.0
   */
  public $settings = null;

  /**
   * The version number.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $_version;

  /**
   * The token.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $_token;

  /**
   * The main plugin file.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $file;

  /**
   * The main plugin directory.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $dir;

  /**
   * The plugin assets directory.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $assets_dir;

  /**
   * The plugin assets URL.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $assets_url;

  /**
   * Suffix for Javascripts.
   * @var     string
   * @access  public
   * @since   1.0.0
   */
  public $script_suffix;

  /**
   * Constructor function.
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function __construct( $file = '', $version = '1.0.0' ) {
    $this->_version = $version;
    $this->_token   = 'cegauto_kalkulator';

    // Load plugin environment variables
    $this->file       = $file;
    $this->dir        = dirname( $this->file );
    $this->assets_dir = trailingslashit( $this->dir ) . 'dist';
    $this->assets_url = esc_url( trailingslashit( plugins_url( '/dist/', $this->file ) ) );

    $this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    register_activation_hook( $this->file, array( $this, 'install' ) );

    add_filter( 'script_loader_tag', [ $this, 'add_async_defer_attribute' ], 10, 2 );

    // Load frontend JS & CSS
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

    // MiniCRM Hook
    add_action('wp_ajax_send_data_to_minicrm', array($this,'send_data_to_minicrm'));    // If called from admin panel
    add_action('wp_ajax_nopriv_send_data_to_minicrm', array($this,'send_data_to_minicrm'));

    // Load API for generic admin functions
    if ( is_admin() ) {
      $this->admin = new CegautoKalkulator_Admin_API();
    }

    // Handle localisation
    $this->load_plugin_textdomain();
    add_action( 'init', array( $this, 'load_localisation' ), 0 );
  } // End __construct ()

  /**
   * Load plugin textdomain
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function load_plugin_textdomain() {
    $domain = 'cegauto-kalkulator';

    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
    load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
  }

  /**
   * Main CegautoKalkulator Instance
   *
   * Ensures only one instance of CegautoKalkulator is loaded or can be loaded.
   *
   * @return Main CegautoKalkulator instance
   * @see cCegautoKalkulator()
   * @since 1.0.0
   * @static
   */
  public static function instance( $file = '', $version = '1.0.0' ) {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self( $file, $version );
    }

    return self::$_instance;
  } // End enqueue_styles ()

  /**
   * Wrapper function to register a new post type
   *
   * @param string $post_type Post type name
   * @param string $plural Post type item plural name
   * @param string $single Post type item single name
   * @param string $description Description of post type
   *
   * @return object              Post type class object
   */
  public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

    if ( ! $post_type || ! $plural || ! $single ) {
      return;
    }

    $post_type = new CegautoKalkulator_Post_Type( $post_type, $plural, $single, $description, $options );

    return $post_type;
  } // End enqueue_scripts ()

  /**
   * Load frontend CSS.
   * @access  public
   * @return void
   * @since   1.0.0
   */
  public function enqueue_styles() {
    wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
    wp_enqueue_style( $this->_token . '-frontend' );
    wp_register_style( $this->_token . '-font', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600,700&display=swap&subset=latin-ext', array(), $this->_version );
    wp_enqueue_style( $this->_token . '-font' );


  } // End admin_enqueue_styles ()

  /**
   * Load frontend Javascript.
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function enqueue_scripts() {
    //REGISTER SCRIPTS
    wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend.js', array( 'jquery' ), $this->_version );
    wp_register_script( $this->_token . '-google', "https://www.google.com/recaptcha/api.js?hl=hu", array() );

    //LOCALIZE SCRIPTS
    /**
     * Localize 'Általános' settings
     */
    wp_localize_script( $this->_token . '-frontend', 'AdminAltalanos', [
      'Kamat'          => get_option( 'cak_kamat_field' ),
      'Casco'          => get_option( 'cak_casco_field' ),
      'Vizsgacimke'    => get_option( 'cak_torzs_forgalmi_rendszam_vizsgacimke_field' ),
      'Uzembehelyezes' => get_option( 'cak_uzembehelyezes_field' ),
      'Flottadij'      => get_option( 'cak_flottadij_field' )
    ] );

    /**
     * Localize 'API' settings
     */
    wp_localize_script( $this->_token . '-frontend', 'cakApi', [
      'url'      => get_option( $this->settings->base . 'url' ),
      'username' => get_option( $this->settings->base . 'username' ),
      'password' => get_option( $this->settings->base . 'password' ),
      'tyurl'    => get_option( $this->settings->base . 'tyurl' ),
    ] );

    /**
     * Localize 'Regisztrációs Adó' Settings
     *
     * First get field values / ranges (min, max, val) and store them temporarily.
     * Localize field values / ranges.
     */
    $regado_ranges = array();
    for ( $i = 1; $i <= get_option( 'cak_regado_db' ); $i++ ) {
      array_push(
        $regado_ranges,
        array(
          //min, max, val
          'min' => get_option( 'cak_regado_kw_min_' . $i ),
          'max' => get_option( 'cak_regado_kw_max_' . $i ),
          'val' => get_option( 'cak_regado_kw_ar_' . $i ),
        )
      );
    }
    wp_localize_script( $this->_token . '-frontend', 'AdminRegado', [
      'RegadoRanges' => $regado_ranges,
    ] );

    /**
     * Localize 'Cégautó Adó' Settings
     *
     * First get field values / ranges (min, max, val) and store them temporarily.
     * Localize field values / ranges.
     */
    $cegautoado_ranges = array();
    for ( $i = 1; $i <= get_option( 'cak_cegautoado_db' ); $i++ ) {
      array_push(
        $cegautoado_ranges,
        array(
          //min, max, val
          'min' => get_option( 'cak_cegautoado_kw_min_' . $i ),
          'max' => get_option( 'cak_cegautoado_kw_max_' . $i ),
          'val' => get_option( 'cak_cegautoado_kw_ar_' . $i ),
        )
      );
    }
    wp_localize_script( $this->_token . '-frontend', 'AdminCegautoAdo', [
      'CegautoAdoRanges' => $cegautoado_ranges,
    ] );

    /**
     * Localize 'Kötelező Biztosítás' Settings.
     *
     * First get field values / ranges (min, max, val) and store them temporarily.
     * Localize field values / ranges.
     */
    $kotelezobiztositas_ranges = array();
    for ( $i = 1; $i <= get_option( 'cak_kotelezobiztositas_db' ); $i++ ) {
      array_push(
        $kotelezobiztositas_ranges,
        array(
          //min, max, val
          'min' => get_option( 'cak_kotelezobiztositas_kw_min_' . $i ),
          'max' => get_option( 'cak_kotelezobiztositas_kw_max_' . $i ),
          'val' => get_option( 'cak_kotelezobiztositas_kw_ar_' . $i ),
        )
      );
    }
    wp_localize_script( $this->_token . '-frontend', 'AdminKotelezoBiztositas', [
      'KotelezoBiztositasRanges' => $kotelezobiztositas_ranges,
    ] );

    /**
     * Localize 'Vagyonszerzési Illeték' Settings.
     *
     * First get field values / ranges (min, max, val) and store them temporarily.
     * Localize field values / ranges.
     */
    $vagyonszerzesi_illetek_ranges = array();
    for ( $i = 1; $i <= get_option( 'cak_vagyonszerzesiilletek_kw_db' ); $i++ ) {
      array_push(
        $vagyonszerzesi_illetek_ranges,
        array(
          //min, max, val
          'min' => get_option( 'cak_vagyonszerzesiilletek_kw_min_' . $i ),
          'max' => get_option( 'cak_vagyonszerzesiilletek_kw_max_' . $i ),
          'val' => get_option( 'cak_vagyonszerzesiilletek_kw_ar_' . $i ),
        )
      );
    }
    wp_localize_script( $this->_token . '-frontend', 'AdminVagyonszerzesi', [
      'VagyonszerzesiRanges' => $vagyonszerzesi_illetek_ranges,
    ] );

    /**
     * Localize 'MÉ' Settings
     */
    //year 1
    $me_yr1_matrix = array();
    for ( $i = 0; $i < 6; $i++ ) {
      $temp_array = array();
      for ( $j = 0; $j < 5; $j++ ) {
        //get cols && rows
        array_push(
          $temp_array,
          get_option( 'cak_me_yr1_val_' . $i . '_' . $j )
        );
      }
      array_push(
        $me_yr1_matrix,
        $temp_array
      );
    }
    //year 2
    $me_yr2_matrix = array();
    for ( $i = 0; $i < 6; $i++ ) {
      $temp_array = array();
      for ( $j = 0; $j < 5; $j++ ) {
        //get cols && rows
        array_push(
          $temp_array,
          get_option( 'cak_me_yr2_val_' . $i . '_' . $j )
        );
      }
      array_push(
        $me_yr2_matrix,
        $temp_array
      );
    }
    //year 3
    $me_yr3_matrix = array();
    for ( $i = 0; $i < 6; $i++ ) {
      $temp_array = array();
      for ( $j = 0; $j < 5; $j++ ) {
        //get cols && rows
        array_push(
          $temp_array,
          get_option( 'cak_me_yr3_val_' . $i . '_' . $j )
        );
      }
      array_push(
        $me_yr3_matrix,
        $temp_array
      );
    }
    //year 4
    $me_yr4_matrix = array();
    for ( $i = 0; $i < 6; $i++ ) {
      $temp_array = array();
      for ( $j = 0; $j < 5; $j++ ) {
        //get cols && rows
        array_push(
          $temp_array,
          get_option( 'cak_me_yr4_val_' . $i . '_' . $j )
        );
      }
      array_push(
        $me_yr4_matrix,
        $temp_array
      );
    }
    //year 5
    $me_yr5_matrix = array();
    for ( $i = 0; $i < 6; $i++ ) {
      $temp_array = array();
      for ( $j = 0; $j < 5; $j++ ) {
        //get cols && rows
        array_push(
          $temp_array,
          get_option( 'cak_me_yr5_val_' . $i . '_' . $j )
        );
      }
      array_push(
        $me_yr5_matrix,
        $temp_array
      );
    }
    //localize
    wp_localize_script( $this->_token . '-frontend', 'AdminMaradvanyertek', [
      'egyEvMatrix'   => $me_yr1_matrix,
      'ketEvMatrix'   => $me_yr2_matrix,
      'haromEvMatrix' => $me_yr3_matrix,
      'negyEvMatrix'  => $me_yr4_matrix,
      'otEvMatrix'    => $me_yr5_matrix
    ] );

    //ENQUEUE SCRIPTS
    wp_enqueue_script( $this->_token . '-frontend' );
    wp_enqueue_script( $this->_token . '-google' );
  } // End admin_enqueue_scripts ()

  /**
   * Load admin CSS.
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function admin_enqueue_styles( $hook = '' ) {
    wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', array(), $this->_version );
    wp_enqueue_style( $this->_token . '-admin' );
  } // End load_localisation ()

  public function add_async_defer_attribute( $tag, $handle ) {
    if ( 'cegauto_kalkulator-google' !== $handle ) {
      return $tag;
    }

    return str_replace( ' src', ' async defer src', $tag );
  }

  /**
   * Load plugin localisation
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function load_localisation() {
    load_plugin_textdomain( 'cegauto-kalkulator', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
  } // End instance ()

  /**
   * Cloning is forbidden.
   *
   * @since 1.0.0
   */
  public function __clone() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
  } // End __clone ()

  /**
   * Unserializing instances of this class is forbidden.
   *
   * @since 1.0.0
   */
  public function __wakeup() {
    _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
  } // End __wakeup ()

  /**
   * Installation. Runs on activation.
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function install() {
    $this->_log_version_number();
  } // End install ()

  /**
   * Log the plugin version number.
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  private function _log_version_number() {
    update_option( $this->_token . '_version', $this->_version );
  } // End _log_version_number () 

  /**
   * Send form data to Mini CRM.
   * @access  public
   * @return  void
   * @since   1.0.0
   */
  public function send_data_to_minicrm() {

    $host = get_option( $this->settings->base . 'mini_crm_url' );
    $system_id = get_option( $this->settings->base . 'system_id' );
    $api_key = get_option( $this->settings->base . 'api_key' );
    $Url = 'https://'.$system_id.':'.$api_key.'@'.$host;
    //Creating the array for the parameters
  
    $Params = array(
               "FirstName"=>$_POST['data']['mssys_fullname'],
               "LastName"=>"",
               "Type"=>"Person",
               "Email"=>@$_POST['data']['email'],
               "Phone"=>@$_POST['data']['mssys_phone']
               
              );
   // Test $contact_url  = "https://22366:Dd6J0heVtSmTF1p7xIOyGsXMiCnUBPZj@r3-test.minicrm.hu/Api/R3/Contact";
	//$contact_url  = "https://22366:mJPj6MaNlAnpuTtSUbvzYeKDOExXQ9sG@r3.minicrm.hu/Api/R3/Contact";
	$contact_url = 'https://'.$system_id.':'.$api_key.'@r3.minicrm.hu/Api/R3/Contact';
	
	
    $contact_info = $this->excute_curl_request($contact_url,$Params); 
    $contact_id = json_decode($contact_info, true);
   
   
    $uzemanyag = '';
    if($_POST['data']['uzemanyag'] == 'dizel'){
      $uzemanyag = 2213;
    }
    if($_POST['data']['uzemanyag'] == 'benzin'){
      $uzemanyag = 2209;
    }
    if($_POST['data']['uzemanyag'] == 'hibrid'){
      $uzemanyag = 2210;
    }
    if($_POST['data']['uzemanyag'] == 'elektromos'){
      $uzemanyag = 2212;
    }
	
	
    $gepjarmujellege = '';
    if($_POST['data']['gepjarmu_jellege'] == 'sedan'){
      $gepjarmujellege = 2214;
    }
    if($_POST['data']['gepjarmu_jellege'] == 'kombi'){
      $gepjarmujellege = 2215;
    }
    if($_POST['data']['gepjarmu_jellege'] == 'ferdehatu'){
      $gepjarmujellege = 2216;
    }
    if($_POST['data']['gepjarmu_jellege'] == 'varositerepjaro'){
      $gepjarmujellege = 2217;
    }
    if($_POST['data']['gepjarmu_jellege'] == 'egyteru'){
      $gepjarmujellege = 2218;
    }
    if($_POST['data']['gepjarmu_jellege'] == 'sportauto'){
      $gepjarmujellege = 2219;
    }
    if($_POST['data']['gepjarmu_jellege'] == 'terepjaro'){
      $gepjarmujellege = 2220;
    }
	
	
	
	
    $kalkulaltOnero = str_replace('Ft','', @$_POST['data']['kalkulalt_onero']);
    $kalkulaltOnero = str_replace('.','', @$_POST['data']['kalkulalt_onero']);
    $kalkulaltHavidij = str_replace("Ft","",@$_POST['data']['kalkulalt_havidij']);
    $kalkulaltHavidij = str_replace("*","",@$_POST['data']['kalkulalt_havidij']);
    $kalkulaltHavidij = str_replace(".","",@$_POST['data']['kalkulalt_havidij']);
    $Params = array(
      'CategoryId'=>12,
      'ContactId'=>$contact_id['Id'],
      'Name' => @$_POST['data']['mssys_fullname'],
      'Telefonszam'=>@$_POST['data']['mssys_phone'],
      'StatusId'=>2653,
      'BruttoVetelar'=> (int)@$_POST['data']['brutto_vetelar'],
      'Onero'=>(int)@$_POST['data']['onero'],
      'Futamido'=>@$_POST['data']['futamido'],
      'Futamido2'=>@$_POST['data']['futasteljesitmeny'],
      'Motorteljesitmeny'=>(int)@$_POST['data']['motor_teljesitmeny'],
      'Uzemanyag'=>$uzemanyag,

      'GepjarmuJellege'=>$gepjarmujellege,
	  'KalkulaltOnero'=>(int)$kalkulaltOnero,
      'KalkulaltHaviDij'=>(int)$kalkulaltHavidij,
      'Cegnev' => @$_POST['data']['mssys_company'],
      'AjanlatForrasa' =>2320,
      'RendelkezikLezartEvvel' => ($_POST['data']['min1lezart_ev'] == "igen") ? "2221" : "2222",
      'KerVisszahivast' => ($_POST['data']['ker_visszahivast'] == "igen") ? "2225" : "2226",
      'Gyartmany' => @$_POST['data']['milyen_gyartmany_erdekli'],
      'KeszletesAuto'=>($_POST['data']['keszletes_auto_erdekel'] == "true") ? "2229" : "2230",   
      'AdatkezelestElfogadja'=>($_POST['data']['elfogadom_az_adatkezelesi_szabalyzatot'] == "true") ? "2237" : "2238",
      'Deleted' => "0",

	  'DateTime1145'=>date('Y . m.d H:i', strtotime('1 hour')),
      'HirleveltKer'=>($_POST['data']['feliratkozom_hirlevelre'] == "true") ? "2233" : "2234",
      'UserId'=>75941
    );
  //   echo '<pre>'; print_r($Params); die;

	$result = $this->excute_curl_request($Url,$Params);

    $turl = array('return_url'=>get_option( $this->settings->base . 'mini_tyurl'));
    echo json_encode($turl,JSON_UNESCAPED_SLASHES);
    die();
  }

  public function excute_curl_request($Url,$Params){

    //Initializing the Curl
    $Curl = curl_init();
    curl_setopt($Curl, CURLOPT_RETURNTRANSFER , true);
    curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER , false);

    //Encoding the array to JSON
    $Params = json_encode($Params);

    //Setting up the headers (Content type, lenght and character encoding)
    curl_setopt($Curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: '.strlen($Params), 'charset=UTF-8'));

    //Setting the Curl request type to PUT
    curl_setopt($Curl, CURLOPT_CUSTOMREQUEST, "PUT");

    //Handover the array to the Curl
    curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);

    //Handover the URL to the Curl
    curl_setopt($Curl, CURLOPT_URL, $Url);

    //Executing the Curl request
    $Response = curl_exec($Curl);
   // echo $Response;
    //Check if any error occurred during the request
    if(curl_errno($Curl)) $Error = "Error: ".curl_error($Curl);

    //Request for the HTML response code
    $ResponseCode = curl_getinfo($Curl, CURLINFO_HTTP_CODE);
    if($ResponseCode != 200) $Error = "API error code: {$ResponseCode} - Message: {$Response}";

    //Closing the Curl
    curl_close($Curl);
    return $Response;
  }

}
