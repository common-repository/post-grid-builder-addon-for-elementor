<?php
if (!defined('ABSPATH'))
  {
    exit;
  }
  final class PGGGO_Elementor_Pgggo_Extension {

  	/**
  	 * Plugin Version
  	 *
  	 * @since 1.0.0
  	 *
  	 * @var string The plugin version.
  	 */
  	const PGGGO_VERSION = '7.6.0';

  	/**
  	 * Minimum Elementor Version
  	 *
  	 * @since 1.0.0
  	 *
  	 * @var string Minimum Elementor version required to run the plugin.
  	 */
  	const PGGGO_MINIMUM_ELEMENTOR_VERSION = '3.0.9';

  	/**
  	 * Minimum PHP Version
  	 *
  	 * @since 1.0.0
  	 *
  	 * @var string Minimum PHP version required to run the plugin.
  	 */
  	const PGGGO_MINIMUM_PHP_VERSION = '7.0';

  	/**
  	 * Instance
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access private
  	 * @static
  	 *
  	 */
  	private static $pgggo_instance = null;

  	/**
  	 * Instance
  	 *
  	 * Ensures only one instance of the class is loaded or can be loaded.
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 * @static
  	 *
  	 */
  	public static function pgggo_instance() {

  		if ( is_null( self::$pgggo_instance ) ) {
  			self::$pgggo_instance = new self();
  		}
  		return self::$pgggo_instance;

  	}

  	/**
  	 * Constructor
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function __construct() {

  		add_action( 'init', [ $this, 'pgggo_i18n' ] );
  		add_action( 'plugins_loaded', [ $this, 'pgggo_init' ] );

  	}

  	/**
  	 * Load Textdomain
  	 *
  	 * Load plugin localization files.
  	 *
  	 * Fired by `init` action hook.
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_i18n() {

  		load_plugin_textdomain( 'pgggo' );

  	}

  	/**
  	 * Initialize the plugin
  	 *
  	 * Load the plugin only after Elementor (and other plugins) are loaded.
  	 * Checks for basic plugin requirements, if one check fail don't continue,
  	 * if all check have passed load the files required to run the plugin.
  	 *
  	 * Fired by `plugins_loaded` action hook.
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_init() {

  		// Check if Elementor installed and activated
  		if ( ! did_action( 'elementor/loaded' ) ) {
  			add_action( 'admin_notices', [ $this, 'pgggo_admin_notice_missing_main_plugin' ] );
  			return;
  		}

  		// Check for required Elementor version
  		if ( ! version_compare( ELEMENTOR_VERSION, self::PGGGO_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
  			add_action( 'admin_notices', [ $this, 'pgggo_admin_notice_minimum_elementor_version' ] );
  			return;
  		}

  		// Check for required PHP version
  		if ( version_compare( PHP_VERSION, self::PGGGO_MINIMUM_PHP_VERSION, '<' ) ) {
  			add_action( 'admin_notices', [ $this, 'pgggo_admin_notice_minimum_php_version' ] );
  			return;
  		}

  		// Add Plugin actions
  		add_action( 'elementor/widgets/widgets_registered', [ $this, 'pgggo_init_widgets' ] );
  		add_action( 'elementor/controls/controls_registered', [ $this, 'pgggo_init_controls' ] );
      add_action('elementor/frontend/after_register_scripts', [$this, 'pgggo_run_script']);
      add_action( 'elementor/frontend/after_enqueue_styles', [$this, 'pgggo_run_styles']);

      add_action( 'wp_ajax_pgggo_ajax_pagination_loader',  array($this, 'pgggo_ajax_loader_pagination_callback'));
      add_action( 'wp_ajax_nopriv_pgggo_ajax_pagination_loader', array($this, 'pgggo_ajax_loader_pagination_callback') );

      //sorting
      add_action( 'wp_ajax_pgggo_ajax_sorting_loader',  array($this, 'pgggo_ajax_loader_sorting_callback'));
      add_action( 'wp_ajax_nopriv_pgggo_ajax_sorting_loader', array($this, 'pgggo_ajax_loader_sorting_callback') );
  	}

    public function pgggo_ajax_loader_pagination_callback()
    {
        check_ajax_referer( 'pgggo_ajax_loader_nonce', 'nonce' );
        if (isset($_POST['pgggosettings'])) {
          $set = $_POST['pgggosettings'];
          $set = stripslashes($set);
          $data_settings = json_decode($set, true);
        }else{
         $data_settings = "";
        }
        if (isset($_POST['pgggopage'])) {
        $pagenumber = (int)$_POST['pgggopage'];
        }else{
         $pagenumber = '';
        }
        if (isset($_POST['pagesortorderdecnet'])) {
          $pagesortorderdecnet = $_POST['pagesortorderdecnet'];
        }else{
          $pagesortorderdecnet = '';
        }

        if (isset($_POST['pagesortorderaccent'])) {
          $pagesortorderaccend = $_POST['pagesortorderaccent'];
        }else{
          $pagesortorderaccend = '';
        }

        if (isset($_POST['pagetaxondata'])) {
          $pagetaxondata = $_POST['pagetaxondata'];
        }else{
          $pagetaxondata = "";
        }

        if (isset($_POST['pagetaxondataselect'])) {
          $pagetaxondataselect = $_POST['pagetaxondataselect'];
        }else{
          $pagetaxondataselect = "";
        }


        if (isset($_POST['pgggoargspass'])) {
          $pggo_args_temp = $_POST['pgggoargspass'];
        }else{
          $pggo_args_temp = "";
        }

        $settings = $data_settings;
        $pgggo_user_data = new \PGGGOCORENS\Pgggo();
        require plugin_dir_path( __FILE__ ) . 'postgrid2/render/pgggo-ajax-pagination.php';
        wp_die();
    }

    public function pgggo_ajax_loader_sorting_callback()
    {
        check_ajax_referer( 'pgggo_ajax_loader_nonce', 'nonce' );
        if (isset($_POST['pgggosettings'])) {
          $set = $_POST['pgggosettings'];
          $set = stripslashes($set);
          $data_settings = json_decode($set, true);
        }else{
         $data_settings = "";
        }
        if (isset($_POST['pgggopage'])) {
        $pagenumber = (int)$_POST['pgggopage'];
        }else{
         $pagenumber = '';
        }
        if (isset($_POST['pagesortorderdecnet'])) {
          $pagesortorderdecnet = $_POST['pagesortorderdecnet'];
        }else{
          $pagesortorderdecnet = '';
        }

        if (isset($_POST['pagesortorderaccent'])) {
          $pagesortorderaccend = $_POST['pagesortorderaccent'];
        }else{
          $pagesortorderaccend = '';
        }

        if (isset($_POST['pagetaxondata'])) {
          $pagetaxondata = $_POST['pagetaxondata'];
        }else{
          $pagetaxondata = "";
        }

        if (isset($_POST['pagetaxondataselect'])) {
          $pagetaxondataselect = $_POST['pagetaxondataselect'];
        }else{
          $pagetaxondataselect = "";
        }

        $settings = $data_settings;
        $pgggo_user_data = new \PGGGOCORENS\Pgggo();
        require plugin_dir_path( __FILE__ ) . 'postgrid2/render/pgggo-ajax-sorting.php';
        wp_die();
    }



    public function pgggo_run_script()
    {
        wp_register_script('pgggo-transition-ui-js', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/js/pgggo-transition.min.js', ['jquery',], '1.0', false);
        wp_enqueue_script('pgggo-transition-ui-js');

        wp_register_script('pgggo-select-ui-js', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/js/pgggo-dropdown.min.js', ['jquery','pgggo-transition-ui-js'], '1.0', false);
        wp_enqueue_script('pgggo-select-ui-js');

        wp_register_script('pgggo-core-jquery', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/js/pgggo-core-jquery.js', ['jquery','pgggo-select-ui-js'], '1.0', false);
        wp_enqueue_script('pgggo-core-jquery');
    }

    public function pgggo_run_styles()
    {
      wp_register_style('pgggo-transition-ui-css', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/css/pgggo-transition.min.css');
      wp_enqueue_style('pgggo-transition-ui-css');

        wp_register_style('pgggo-select-ui-css', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/css/pgggo-dropdown.min.css');
        wp_enqueue_style('pgggo-select-ui-css');

        wp_register_style('pgggo-core-css', plugin_dir_url(PGGGO_PLUGIN_FILE) . 'includes/css/pgggo_core.min.css');
        wp_enqueue_style('pgggo-core-css');
    }

  	/**
  	 * Admin notice
  	 *
  	 * Warning when the site doesn't have Elementor installed or activated.
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_admin_notice_missing_main_plugin() {

  		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

  		$pgggo_message = sprintf(
  			/* translators: 1: Plugin name 2: Elementor */
  			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'pgggo' ),
  			'<strong>' . esc_html__( 'Elementor Post Grid By Greeky Green Owl', 'pgggo' ) . '</strong>',
  			'<strong>' . esc_html__( 'Elementor', 'pgggo' ) . '</strong>'
  		);

  		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $pgggo_message );

  	}

  	/**
  	 * Admin notice
  	 *
  	 * Warning when the site doesn't have a minimum required Elementor version.
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_admin_notice_minimum_elementor_version() {

  		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

  		$pgggo_message = sprintf(
  			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
  			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'pgggo' ),
  			'<strong>' . esc_html__( 'Elementor Post Grid by Geeky Green Owl', 'pgggo' ) . '</strong>',
  			'<strong>' . esc_html__( 'Elementor', 'pgggo' ) . '</strong>',
  			 self::PGGGO_MINIMUM_ELEMENTOR_VERSION
  		);

  		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $pgggo_message );

  	}

  	/**
  	 * Admin notice
  	 *
  	 * Warning when the site doesn't have a minimum required PHP version.
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_admin_notice_minimum_php_version() {

  		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

  		$pgggo_message = sprintf(
  			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
  			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'pgggo' ),
  			'<strong>' . esc_html__( 'Elementor Post Grid by Geeky Green Owl', 'pgggo' ) . '</strong>',
  			'<strong>' . esc_html__( 'PHP', 'pgggo' ) . '</strong>',
  			 self::PGGGO_MINIMUM_PHP_VERSION
  		);

  		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $pgggo_message );

  	}

  	/**
  	 * Init Widgets
  	 *
  	 * Include widgets files and register them
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_init_widgets() {
      //core imager

      require_once( __DIR__ . '/class-pgggo-image-render.php');

      require_once( __DIR__ . '/postgrid2/pgggo_post_grid_widget2.php' );

      \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor\Elementor_Pgggo_Post_Grid2() );

  	}

  	/**
  	 * Init Controls
  	 *
  	 * Include controls files and register them
  	 *
  	 * @since 1.0.0
  	 *
  	 * @access public
  	 */
  	public function pgggo_init_controls() {
        // NOt required
  	}

  }

  PGGGO_Elementor_Pgggo_Extension::pgggo_instance();


function pgggo_add_elementor_widget_categories($pgggo_elements_manager)
{

    $pgggo_elements_manager->add_category(
        'pgggo-category',
        [
            'title' => esc_html__('Post Grid By Geeky Green Owl', 'pgggo'),
            'icon'  => 'fa fa-braille',
        ]
    );

}
add_action('elementor/elements/categories_registered', 'pgggo_add_elementor_widget_categories');


// allows to use the grid in single blog contents
function pgggo_fix_usage_single_posts(){
  $args = array(
      'public' => true,
  );
  $output   = 'names'; // 'names' or 'objects' (default: 'names')
  $operator = 'and'; // 'and' or 'or' (default: 'and')

  $post_types = get_post_types($args, $output, $operator);
	if ( is_singular( $post_types ) ) {
			global $wp_query;
			$page = ( int ) $wp_query->get( 'page' );
			if ( $page > 1 ) {
					// convert 'page' to 'paged'
					$wp_query->set( 'page', 1 );
					$wp_query->set( 'paged', $page );
			}
			// prevent redirect
			remove_action( 'template_redirect', 'redirect_canonical' );
	}
}
add_action( 'template_redirect', 'pgggo_fix_usage_single_posts',0 );




function pgggo_admin_notice_pro_version_availablity(){

  /* Check transient, if available display notice */
  if( get_transient( 'pgggo-admin-notice-check-pro' ) ){

      /* Delete transient, only display this notice once. */
      delete_transient( 'pgggo-admin-notice-check-pro' );
  }
}

/* Add admin notice */
add_action( 'admin_notices', 'pgggo_admin_notice_pro_version_availablity' );
