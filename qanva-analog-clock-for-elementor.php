<?php
/**
 * Plugin Name: Qanva Analog Clock for Elementor
 * Description: Add an analog clock to your website
 * Plugin URI:  https://qanva.tech/analog-clock-for-elementor
 * Version:     1.1.1
 * Author:      ukischkel, fab22
 * Author URI:  https://qanva.tech
 * License:		GPL v2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: qanva-analog-clock-for-elementor
 * Domain Path: languages
 * Elementor tested up to: 3.15.3
 * Elementor Pro tested up to: 3.15.1
 */
namespace MAKEANALOGCLOCK;

	define( 'MAKEANALOGCLOCKVERSION', '1.1.1' );
  
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
	
    $name = __( 'Analog Clock Widget for Elementor', 'qanva-analog-clock-for-elementor' );
    $desc = __( 'Add an analog clock to your website', 'qanva-analog-clock-for-elementor' );
        

final class MAKEANALOGCLOCKELEMENTOR{
	const  MINIMUM_ELEMENTOR_VERSION = '2.0.0' ;
  const  MINIMUM_PHP_VERSION = '7.0' ;
  private static  $_instance = null ;
    public static function instance(){
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct(){
        add_action( 'plugins_loaded', [ $this,'ladesprachdateifueranalogclockforelementor'] );
        add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
    }
    
    public function ladesprachdateifueranalogclockforelementor() {
      $pfad = dirname( plugin_basename(__FILE__) ) . '/languages/';
      load_plugin_textdomain( 'qanva-analog-clock-for-elementor', false, $pfad );
    } 

    
    public function on_plugins_loaded(){
        if ( $this->is_compatible() ) {
            add_action( 'elementor/init', [ $this, 'init' ] );
        }
    }

		/** Check required min versions **/
    public function is_compatible(){     
        if ( !did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return false;
        }
        if ( !version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return false;
        }
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return false;
        }        
        return true;
    }
    
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
          esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'qanva-analog-clock-for-elementor' ),
          '<strong>' . esc_html__( 'Analog Clock for Elementor', 'qanva-analog-clock-for-elementor' ) . '</strong>',
          '<strong>' . esc_html__( 'Elementor', 'qanva-analog-clock-for-elementor' ) . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
    
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
          esc_html__( '"%1$s" requires min version "%2$s" of Elementor to be installed.', 'qanva-analog-clock-for-elementor' ),
          '<strong>' . esc_html__( 'Analog Clock for Elementor', 'qanva-analog-clock-for-elementor' ) . '</strong>',
          '<strong>' . MINIMUM_ELEMENTOR_VERSION . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
    
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
        $message = sprintf(
          esc_html__( '"%1$s" requires min PHP version "%2$s" running.', 'qanva-analog-clock-for-elementor' ),
          '<strong>' . esc_html__( 'Analog Clock for Elementor', 'qanva-analog-clock-for-elementor' ) . '</strong>',
          '<strong>' . MINIMUM_PHP_VERSION . '</strong>'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }
    
    public function init(){
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets_ace' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'analogclock_styles' ] );
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'analogclock_script' ] );
    }
		
		    
    /** Widgets **/		
    private function analogclock_init_controls() {
        require_once( __DIR__ . '/controls/clockcontrols.php' );
    }		
    
    public function register_widgets_ace(){
        $this->analogclock_init_controls();
        \Elementor\Plugin::instance()->widgets_manager->register( new Widgets\MAKEANALOGCLOCKFORTHEELEMENTOR() );
    }
       
	
    public function analogclock_styles() {
        wp_enqueue_style( 'analogclockprev', plugins_url( 'controls/css/qanvclockpanel.css', __FILE__ ), [ 'elementor-editor' ], MAKEANALOGCLOCKVERSION);
        wp_enqueue_style( 'qanva_clock_chosen',plugins_url( 'controls/css/chosen.min.css', __FILE__ ),[ 'elementor-editor' ], MAKEANALOGCLOCKVERSION);
		}	
  
    public function analogclock_script() {
        wp_enqueue_script('chosenjs', plugins_url( 'controls/js/chosen.jquery.min.js', __FILE__ ), [ 'jquery'], MAKEANALOGCLOCKVERSION );
        wp_enqueue_script('chosen', plugins_url( 'controls/js/chosenstart.js', __FILE__ ), ['jquery'], MAKEANALOGCLOCKVERSION);
		}
		
}

 MAKEANALOGCLOCKELEMENTOR::instance();
 