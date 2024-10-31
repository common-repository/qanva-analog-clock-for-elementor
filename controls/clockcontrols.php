<?php
namespace MAKEANALOGCLOCK\Widgets;

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Element_Base;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

use Elementor\Widget_Base;
use Elementor\Core\Schemes\Typography;
use \DateTimeZone;
use \DateTime;

if ( ! defined( 'ABSPATH' ) ) exit; 

class MAKEANALOGCLOCKFORTHEELEMENTOR extends Widget_Base{
	public function get_name(){
		return 'ace_custommouse';
	}

	public function get_title(){
		return 'Analog Clock';
	}

	public function get_icon(){
		return 'eicon-clock-o';
	}

	public function get_categories(){
		return [ 'basic' ];
	}	
	
	public function __construct($data = [], $args = null) {
			parent::__construct($data, $args);
			wp_register_script( 'qanvaclockjs', plugin_dir_url( __DIR__ ) . 'controls/js/qclock.js', [ 'jquery','elementor-frontend' ], '1.0.0', true );
	}
	
	public function get_script_depends() {
			return [ 'qanvaclockjs' ];
	}

	protected function register_controls(){
		$this->start_controls_section(
			 'section_content',
			 [
				 'label' => 'Analog Clock<style>.elementor-panel-heading-title{width:100%}</style><img src="' . plugins_url('img/qanvalogo.svg',__FILE__) . '" style="width:25px;float:right;margin:-5px;" >',
			 ]
		);	
		 
		$regions = array(
		  'Africa' => DateTimeZone::AFRICA,
		  'America' => DateTimeZone::AMERICA,
		  'Antarctica' => DateTimeZone::ANTARCTICA,
		  'Aisa' => DateTimeZone::ASIA,
		  'Atlantic' => DateTimeZone::ATLANTIC,
		  'Europe' => DateTimeZone::EUROPE,
		  'Indian' => DateTimeZone::INDIAN,
		  'Pacific' => DateTimeZone::PACIFIC
		);			 

		function tz_list() {
				$zones_array = array();
				$timestamp = time();
						foreach(timezone_identifiers_list() as $key => $zone) {
								date_default_timezone_set($zone);
								$dift = str_replace(0,'',date('O', $timestamp));
								if($dift == '+'){
									$dift = '+0';
								}
								$zones_array[$key]['zone'] = $zone;
								$zones_array[$key]['diff_from_GMT'] = $dift;
						}
				return $zones_array;
		}	
		
		$timezones = ['nix' => __( 'Select Timezone', 'qanva-analog-clock-for-elementor' )];
		foreach ($regions as $name => $mask){
			$zones = DateTimeZone::listIdentifiers($mask);
			static $gmtcheck = 0;
				foreach($zones as $timezone){
					if(explode('/',$timezone)[0] == 'Europe' && $gmtcheck == 0){
						$timezones['+0*Europe/GMT'] = 'Europe/GMT';
						$gmtcheck = 1;
					}
					$time = new DateTime('', new DateTimeZone($timezone));
					$date = new DateTime();
					$time_Zone = $date->getTimezone();
					$local_tz = new DateTimeZone($time_Zone->getName());
					$local = new DateTime('now', $local_tz);
					$user_tz = new DateTimeZone($timezone);
					$user = new DateTime('now', $user_tz);
					$local_offset = $local->getOffset() / 3600;
					$user_offset = $user->getOffset() / 3600;
					$diff = $user_offset - $local_offset;
					if('-' != substr($diff,0,1)){
						$diff = '+' . $diff;
					}
					$timezones[$diff . '*' . $timezone] = $timezone;
				}
		}
		
		foreach(tz_list() as $t) { 
    $timezones[$t['diff_from_GMT'] . '*' . $t['zone']] = $t['zone'];
  	}
					
		$this->add_control(
			'qanva_ace_times',
			[
				'label' => __( 'Timezone', 'qanva-analog-clock-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'nix',
				'options' => 	$timezones,
			]
		);		
		
		$this->add_control(
			'qanva_ace_clocksize',
			[
				'label' => __( 'Clock size', 'qanva-analog-clock-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
     	'min' => 120,
						'max' => 400,
						'step' => 1,
					],
    ],
    'default' => [
						'unit' => 'px',
						'size' => 200,
				],
				'size_units' => [ 'px' ],
			]
  );
		       

		$this->add_control(
			'qanva_ace_color',
			[
				'label' => __( 'Clock Background', 'qanva-analog-clock-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0)',
    'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
			]
		);	
	
		$this->add_control(
			'qanva_ace_arms',
			[
				'label' => __( 'Hour and Minute Color', 'qanva-analog-clock-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,1)',
    'scheme' => [
							'type' => \Elementor\Core\Schemes\Color::get_type(),
							'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
			]
		);					
		
		$this->add_control(
			'qanva_ace_second',
			[
				'label' => __( 'Second Color', 'qanva-analog-clock-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(255,0,0,1)',
    'scheme' => [
							'type' => \Elementor\Core\Schemes\Color::get_type(),
							'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
			]
		);
		
		$this->add_control(
			'qanva_ace_face',
			[
				'label' => __( 'Show Face', 'qanva-analog-clock-for-elementor' ), 
				'description' => __( 'Switch to show a clock face.', 'qanva-analog-clock-for-elementor' ), 
				'separator' => 'before', 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'qanva-analog-clock-for-elementor' ),
				'label_off' => __( 'No', 'qanva-analog-clock-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
		#		'frontend_available' => true,
			]
		);
					
		$this->add_control(
			'qanva_ace_use',
			[
				'label' => __( 'Switch dots/numbers', 'qanva-analog-clock-for-elementor' ), 
				'description' => __( 'Switch between dots or numbers on the clock face.', 'qanva-analog-clock-for-elementor' ), 
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Num', 'qanva-analog-clock-for-elementor' ),
				'label_off' => __( 'Dot', 'qanva-analog-clock-for-elementor' ),
				'return_value' => 'num',
				'default' => 'dot',
		#		'frontend_available' => true,
				'condition' => [
					'qanva_ace_face' => 'yes', 
				],
			]
		);
			
		$this->add_control(
			'qanva_ace_color_face',
			[
				'label' => __( 'Clock Face Color', 'qanva-analog-clock-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,1)',
    'scheme' => [
							'type' => \Elementor\Core\Schemes\Color::get_type(),
							'value' => \Elementor\Core\Schemes\Color::COLOR_1,
			 ],
				'condition' => [
					'qanva_ace_face' => 'yes', 
				],
			]
		);		
				
		$this->add_control(
			'qanva_ace_fontsize',
			[
				'label' => __( 'Fontsize', 'qanva-analog-clock-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
     	'min' => 2,
						'max' => 40,
						'step' => 2,
					],
    ],
    'default' => [
						'size' => 14,
				],
				'condition' => [
					'qanva_ace_face' => 'yes', 
					'qanva_ace_use' => 'num', 
				]
			]
		);
			
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'qanva_ace_typo',
				'exclude' => [
						'font_size','font_style','text_transform','text_decoration','line_height','letter_spacing','word_spacing'
				],
				'condition' => [
					'qanva_ace_face' => 'yes', 
					'qanva_ace_use' => 'num', 
				],
			]
		);	
			
		$this->add_control(
			'qanva_clock_info',
			[
				'label' => '<span class="elementor-panel-heading-title">' . __( 'Info', 'qanva-analog-clock-for-elementor' ) . '!</span>',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => '<div class="elementor-control-field-description"><strong>' . __( 'IMPORTANT' , 'qanva-analog-clock-for-elementor' ) . '</strong><br>' . __( 'Always set a timezone' , 'qanva-analog-clock-for-elementor' ) . '!<br><br>' . __( 'If you see the hands jumping, don\'t worry, this comes from Elementor editor scripts refreshing the content' , 'qanva-analog-clock-for-elementor' ) . '!' . '<hr><div class="qanva-ad">' . __( 'Find more tools at our homepage ' , 'qanva-analog-clock-for-elementor' ) . '<a href="https://qanva.tech" target="_blank">Qanva.tech</a></div></div><style>
								.elementor-control.elementor-control-type-select .elementor-control-input-wrapper::after {
								font-size:0px
								}
								.elementor-control-qanva_ace_times [class^=elementor-control-unit]{
									max-width:100% !important;
									width:100% !important;
								}
								.elementor-control-qanva_ace_times.elementor-label-inline > .elementor-control-content > .elementor-control-field > .elementor-control-input-wrapper{
									margin:0 0 0 30px;
									max-width:100%;
									width:100%
								}
								[data-setting=qanva_ace_times]{width:190px;margin-left:30px}
								</style>',
				'show_label' => true,
				'separator' => 'before',
			]
		);			
	

		 $this->end_controls_section();
	}
	
		protected function render(){
			$settings = $this->get_settings_for_display();
			$tdiff = explode('*',$settings['qanva_ace_times'])[0];
				echo "<style>#qanvaclock{width:" . $settings["qanva_ace_clocksize"]["size"] .
				"px;height:" . $settings["qanva_ace_clocksize"]["size"] . "px;position:relative}#clockcanvas{display:block;margin:auto}
				.clockdigits{position:absolute;padding:0;margin:0;text-align:center;font-weight:bold;color:" . $settings["qanva_ace_color_face"] . 
				";}</style>";
			
			$tdiff = explode('*',esc_html($settings['qanva_ace_times']))[0];
			$fontsize = 20;
			if(isset($settings["qanva_ace_fontsize"]['size'])){
				$fontsize = esc_html($settings["qanva_ace_fontsize"]['size']);
			}
			$newid = time() + mt_rand(111,11111);
				echo "<div id='qanvaclock-" . $newid . "'></div>";
				echo "<style>#qanvaclock-" . $newid . "{width:" . esc_html($settings["qanva_ace_clocksize"]["size"]) .
				"px;height:" . esc_html($settings["qanva_ace_clocksize"]["size"]) . "px;position:relative}#clockcanvas-" . $newid . "{display:block;margin:auto}
				.clockdigits-" . $newid . "{position:absolute;padding:0;margin:0;text-align:center;font-weight:bold;color:" . esc_html($settings["qanva_ace_color_face"]) . 
				";}</style>";
				wp_add_inline_script( 'qanvaclockjs','
									makeqanvaclock(
									"' . esc_html($settings["qanva_ace_clocksize"]["size"]) . '",
									"' . esc_html($settings["qanva_ace_color"]) . '",
									"' . esc_html($settings["qanva_ace_second"]) . '",
									"' . esc_html($settings["qanva_ace_color_face"]) . '",
									"' . esc_html($settings["qanva_ace_clocksize"]["size"])/2 . '",
									"' . esc_html($settings['qanva_ace_use']) . '",
									"' . $tdiff . '",
									"' . esc_html($settings["qanva_ace_second"]) . '",
									"' . esc_html($settings["qanva_ace_arms"]) . '",
									"' . esc_html($settings["qanva_ace_arms"]) . '",
									"' . $fontsize . '",
									"' . $newid . '",
									"' . esc_html($settings['qanva_ace_face']) . '",
									);','after' );
				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {		
					echo '<script>makeqanvaclock(
									"' . esc_html($settings["qanva_ace_clocksize"]["size"]) . '",
									"' . esc_html($settings["qanva_ace_color"]) . '",
									"' . esc_html($settings["qanva_ace_second"]) . '",
									"' . esc_html($settings["qanva_ace_color_face"]) . '",
									"' . esc_html($settings["qanva_ace_clocksize"]["size"])/2 . '",
									"' . esc_html($settings['qanva_ace_use']) . '",
									"' . $tdiff . '",
									"' . esc_html($settings["qanva_ace_second"]) . '",
									"' . esc_html($settings["qanva_ace_arms"]) . '",
									"' . esc_html($settings["qanva_ace_arms"]) . '",
									"' . $fontsize . '",
									"' . $newid . '",
									"' . esc_html($settings['qanva_ace_face']) . '",
									);</script>';	
				}									
		}
		

}