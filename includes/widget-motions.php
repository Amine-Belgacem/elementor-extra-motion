<?php

/**
 * Class Widget_Motions
 *
 * Handles motion effects for widgets using Elementor.
 */
class Widget_Motions {

    /**
     * Initializes the motion effects.
     */
    public static function init() {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts'));    
        add_action(
            'elementor/element/common/_section_style/after_section_end', 
            array(__CLASS__, 'inject_motions'), 10, 2
        );
        add_action(
            'elementor/frontend/widget/before_render', 
            array(__CLASS__, 'render_motions'), 10, 2
        );                
    }

    /**
     * Enqueues the required scripts.
     */
    public static function enqueue_scripts() {
        if (!wp_script_is('extra-motion-float', 'enqueued')) {
            wp_enqueue_script(
                'extra-motion-float', 
                plugin_dir_url(__FILE__) . '../assets/js/extra-motion-float.js', 
                array('jquery'), 
                '1.0', 
                true
            );
        }
    }

    /**
     * Injects motion controls into Elementor sections.
     *
     * @param object $element The Elementor element object.
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
            'floating_enabled',
            [
                'label' => esc_html__( 'Floating Widget', 'extra-motion' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'extra-motion' ),
                'label_off' => esc_html__( 'Off', 'extra-motion' ),
                'return_value' => 'yes',
                'default' => ''
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
     * Renders motion effects for widgets.
     *
     * @param object $element The Elementor element object.
     */
    public static function render_motions($element) {
        $settings = $element->get_settings_for_display();
        $motions = array(
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
