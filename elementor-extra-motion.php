<?php

/**
 * Plugin Name: Elementor Extra Motion
 * Description: This plugin enhances Elementor with extra motion effects.
 * Version:     1.0.3
 * Author:      Md. Amine Belgacem
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Extra_Motion_Plugin
 *
 * Initializes the Elementor Extra Motion plugin and its features.
 */
class Elementor_Extra_Motion {
    
    /**
     * Plugin initialization.
     */
    public static function init() {
        // Define plugin file constant
        define('EXTRA_MOTION_PLUGIN_FILE', __FILE__);
        
        // Include necessary files
        include_once(plugin_dir_path(__FILE__) . 'includes/translations.php');
        include_once(plugin_dir_path(__FILE__) . 'admin/plugin-activation.php');
        include_once(plugin_dir_path(__FILE__) . 'admin/settings.php');
        include_once(plugin_dir_path(__FILE__) . 'includes/entrance-animations.php');
        include_once(plugin_dir_path(__FILE__) . 'includes/layout-motions.php');
        include_once(plugin_dir_path(__FILE__) . 'includes/widget-motions.php');
        
        // Initialize plugin features
        add_action('after_setup_theme', array(__CLASS__, 'initialize_extra_motion'));
    }

    /**
     * Initializes the extra motion features based on plugin settings.
     */
    public static function initialize_extra_motion() {
        $options = get_option('extra_motion_options');
        $features = [
            'layout_motions_enabled' => 'Layout_Motions',
            'widget_motions_enabled' => 'Widget_Motions',
            'entrance_animations_enabled' => 'Entrance_Animations'
        ];

        foreach ($features as $option => $class) {
            if ($options[$option] ?? false) {
                $class::init();
            }
        }
    }
}

// Initialize the plugin
Elementor_Extra_Motion::init();

?>
