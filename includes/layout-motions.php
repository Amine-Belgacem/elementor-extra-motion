<?php

/**
 * Class Layout_Motions
 *
 * Provides functionality to add extra motion effects to Elementor sections.
 */
class Layout_Motions {

    /**
     * Initializes the motion effects by registering actions.
     */
    public static function init() {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));
        add_action(
            'elementor/element/container/section_layout/after_section_end', 
            array(__CLASS__, 'inject_motions'), 10, 2
        );
        add_action(
            'elementor/element/section/section_advanced/after_section_end', 
            array(__CLASS__, 'inject_motions'), 10, 2
        );
        add_action(
            'elementor/frontend/container/before_render', 
            array(__CLASS__, 'render_motions'), 10, 2
        );
        add_action(
            'elementor/frontend/section/before_render', 
            array(__CLASS__, 'render_motions'), 10, 2
        );      
    }

    /**
     * Enqueues necessary scripts and styles.
     */
    public static function enqueue_scripts() {
        wp_enqueue_style(
            'extra-motion-styles', 
            plugin_dir_url(__FILE__) . '../assets/css/extra-motion-styles.css', 
            array(), 
            '1.0'
        );
        wp_enqueue_script(
            'extra-motion-kenburns', 
            plugin_dir_url(__FILE__) . '../assets/js/extra-motion-kenburns.js', 
            array('jquery'), 
            '1.2', 
            true
        );
        wp_enqueue_script(
            'extra-motion-parallax', 
            plugin_dir_url(__FILE__) . '../assets/js/extra-motion-parallax.js', 
            array('jquery'), 
            '1.1', 
            true
        );
        wp_enqueue_script(
            'extra-motion-float', 
            plugin_dir_url(__FILE__) . '../assets/js/extra-motion-float.js', 
            array('jquery'), 
            '1.1', 
            true
        );
    }

    /**
     * Injects motion controls into the Elementor element.
     *
     * @param object $element The Elementor element.
     * @param array $args Additional arguments.
     */
    public static function inject_motions($element, $args) {
        $element->start_controls_section(
            'extra_motion_section',
            [
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
                'label' => esc_html__( 'Extra Motion', 'extra-motion' ),
            ]
        );
 
        $element->add_control(
            'ken_burns_enabled',
            [
                'label' => esc_html__( 'Ken-Burns Zoom', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'extra-motion' ),
                'label_off' => esc_html__( 'Off', 'extra-motion' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'parallax_enabled' => '',
                    'floating_enabled' => '',                
                ],
            ]
        );

        // $element->add_control(
        //     'ken_burns_alert',
        //     [
        //         'type' => \Elementor\Controls_Manager::ALERT,
        //         'alert_type' => 'warning',
        //         'content' => esc_html__(KEN_BURNS_INFO, 'elementor'),
        //         'condition' => [
        //             'ken_burns_enabled' => 'yes',
        //         ],
        //     ]
        // );

        $element->add_control(
            'ken_burns_animation_direction',
            [
                'label' => esc_html__( 'Direction', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'center-center',
                'options' => [
                    'center-center' => esc_html__( 'Center Center', 'extra-motion' ),
                    'center-left' => esc_html__( 'Center Left', 'extra-motion' ),
                    'center-right' => esc_html__( 'Center Right', 'extra-motion' ),
                    'top-center' => esc_html__( 'Top Center', 'extra-motion' ),
                    'top-left' => esc_html__( 'Top Left', 'extra-motion' ),
                    'top-right' => esc_html__( 'Top Right', 'extra-motion' ),
                    'bottom-center' => esc_html__( 'Bottom Center', 'extra-motion' ),
                    'bottom-left' => esc_html__( 'Bottom Left', 'extra-motion' ),
                    'bottom-right' => esc_html__( 'Bottom Right', 'extra-motion' ),
                ],
                'condition' => [
                    'ken_burns_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ken_burns_animation_duration',
            [
                'label' => esc_html__( 'Duration (s)', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 60,
                'step' => 1,
                'default' => 20,
                'condition' => [
                    'ken_burns_enabled' => 'yes',
                ],
            ]
        );
        
        $element->add_control(
            'ken_burns_scale_factor',
            [
                'label' => esc_html__( 'Scale Factor', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'step' => 0.1,
                'default' => 1.3,
                'condition' => [
                    'ken_burns_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ken_burns_infinite',
            [
                'label' => esc_html__( 'Infinite', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'extra-motion' ),
                'label_off' => esc_html__( 'Off', 'extra-motion' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [
                    'ken_burns_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'ken_burns_overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .ken-burns-overlay' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'ken_burns_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'parallax_enabled',
            [
                'label' => esc_html__( 'Vertical Parallax', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'extra-motion' ),
                'label_off' => esc_html__( 'Off', 'extra-motion' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'ken_burns_enabled' => '',
                    'floating_enabled' => '',  
                ],
            ]
        );

        // $element->add_control(
        //     'parallax_alert',
        //     [
        //         'type' => \Elementor\Controls_Manager::ALERT,
        //         'alert_type' => 'warning',
        //         'content' => esc_html__(PARALLAX_INFO, 'elementor'),
        //         'condition' => [
        //             'parallax_enabled' => 'yes',
        //         ],
        //     ]
        // );

        $element->add_control(
            'parallax_translate_factor',
            [
                'label' => esc_html__( 'Translate Factor', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 2,
                'step' => 0.1,
                'default' => 1,
                'condition' => [
                    'parallax_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'parallax_scale_factor',
            [
                'label' => esc_html__( 'Scale Factor', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 2,
                'step' => 0.1,
                'default' => 1,
                'condition' => [
                    'parallax_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'parallax_duration',
            [
                'label' => esc_html__( 'Duration (s)', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
                'default' => 0.3,
                'condition' => [
                    'parallax_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'floating_enabled',
            [
                'label' => esc_html__( 'Floating Widget', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'extra-motion' ),
                'label_off' => esc_html__( 'Off', 'extra-motion' ),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'ken_burns_enabled' => '',
                    'parallax_enabled' => '',
                ],
            ]
        );

        $element->add_control(
            'floating_animation_duration',
            [
                'label' => esc_html__( 'Duration (s)', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 60,
                'step' => 1,
                'default' => 6,
                'condition' => [
                    'floating_enabled' => 'yes',
                ],
            ]
        );

        $element->add_control(
            'floating_animation_distance',
            [
                'label' => esc_html__( 'Vertical Distance', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 20,
                'condition' => [
                    'floating_enabled' => 'yes',
                ],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Renders the motion effects based on user settings.
     *
     * @param object $element The Elementor element.
     */
    public static function render_motions($element) {
        $settings = $element->get_settings_for_display();
        $motions = array(
            'ken_burns' => array(
                'enabled' => !empty($settings['ken_burns_enabled']),
                'attribute' => 'ken-burns-data',
                'class' => 'ken-burns-container',
                'handler' => 'KenBurnsHandler',
                'attributes' => array(
                    'direction' => $settings['ken_burns_animation_direction'],
                    'duration' => $settings['ken_burns_animation_duration'],
                    'scale-factor' => $settings['ken_burns_scale_factor'],
                    'infinite' => !empty($settings['ken_burns_infinite']),
                    'overlay-color' => $settings['ken_burns_overlay_color']
                )
            ),
            'parallax' => array(
                'enabled' => !empty($settings['parallax_enabled']),
                'attribute' => 'parallax-data',
                'class' => 'parallax-container',
                'handler' => 'ParallaxHandler',
                'attributes' => array(
                    'translate-factor' => $settings['parallax_translate_factor'],
                    'scale-factor' => $settings['parallax_scale_factor'],
                    'duration' => $settings['parallax_duration']
                )
            ),
            'floating' => array(
                'enabled' => !empty($settings['floating_enabled']),
                'attribute' => 'floating-data',
                'class' => 'floating-container',
                'handler' => 'FloatingHandler',
                'attributes' => array(
                    'distance' => $settings['floating_animation_distance'],
                    'duration' => $settings['floating_animation_duration']
                )
            )
        );

        foreach ($motions as $motion) {
            if ($motion['enabled']) {
                $element->add_render_attribute('_wrapper', 'class', $motion['class']);
                $element->add_render_attribute(
                    '_wrapper', 
                    $motion['attribute'], 
                    json_encode($motion['attributes'])
                );

                add_action('wp_footer', function () use ($motion) {
                    ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            new <?php echo $motion['handler']; ?>();
                        });
                    </script>
                    <?php
                });

                return;
            }
        }
    }
}

?>