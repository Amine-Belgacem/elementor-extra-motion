<?php

/**
 * Class Entrance_Animations
 *
 * Handles the initialization and injection of entrance animations.
 */
class Entrance_Animations {

    /**
     * Initializes the entrance animations by registering actions and filters.
     */
    public static function init() {
        add_action(
            'wp_enqueue_scripts', 
            array(__CLASS__, 'enqueue_scripts')
        );
        add_filter(
            'elementor/controls/animations/additional_animations', 
            array(__CLASS__, 'inject_entrance_animations')
        );
    }

    /**
     * Enqueues necessary styles for entrance animations.
     */
    public static function enqueue_scripts() {
        wp_enqueue_style(
            'extra-motion-animations',
            plugin_dir_url(__FILE__) . '../assets/css/extra-motion-animations.css',
            array(),
            '1.0'
        );
    }

    /**
     * Injects extra entrance animations into Elementor.
     *
     * @param array $animations The existing animations array.
     * @return array The modified animations array with extra motion animations.
     */
    public static function inject_entrance_animations($animations) {
        $extra_motion_animations = array(
            'Extra Motion' => array(
                'blur-in' => 'Blur In',
                'blur-out' => 'Blur Out',
                'fade-in-up-shorter' => 'Fade In Up Shorter',
                'fade-in-right-shorter' => 'Fade In Right Shorter',
                'fade-in-left-shorter' => 'Fade In Left Shorter',
                'fade-in-down-shorter' => 'Fade In Down Shorter',
                'fade-in-forward' => 'Fade In Forward',
                'fade-in-backward' => 'Fade In Backward',
            ),
        );

        return array_merge($animations, $extra_motion_animations);
    }
}

?>
