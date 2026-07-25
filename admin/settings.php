<?php

/**
 * Class Extra_Motion_Settings
 *
 * This class manages the settings for the Extra Motion plugin.
 */
class Extra_Motion_Settings {

    /**
     * Extra_Motion_Settings constructor.
     *
     * Initializes the class by adding necessary actions and filters.
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));

        add_action('admin_init', array($this, 'register_settings'));

        add_filter(
            'plugin_action_links_' . plugin_basename(EXTRA_MOTION_PLUGIN_FILE),
            array($this, 'add_settings_link')
        );
    }

    /**
     * Adds the Extra Motion submenu page to the WordPress admin menu.
     */
    public function add_admin_menu() {
        add_options_page(
            'Extra Motion Settings',
            'Extra Motion',
            'manage_options',
            'extra-motion-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Registers settings and settings sections for the Extra Motion plugin.
     */
    public function register_settings() {
        // Default options for the Extra Motion plugin
        $default_options = array(
            'layout_motions_enabled' => 'on',
            'widget_motions_enabled' => 'on',
            'entrance_animations_enabled' => 'on',
        );

        // Get existing options or set defaults if none exist
        $existing_options = get_option('extra_motion_options');
        if (false === $existing_options) {
            add_option('extra_motion_options', $default_options);
        }

        // Register settings and settings sections
        register_setting(
            'extra_motion_settings',
            'extra_motion_options',
            array(
                'default' => $default_options,
            )
        );

        add_settings_section(
            'extra_motion_main_section',
            '',
            array($this, 'main_section_callback'),
            'extra_motion_settings'
        );

        add_settings_field(
            'layout_motions_toggle',
            'Layout Motions',
            array($this, 'layout_motions_toggle_callback'),
            'extra_motion_settings',
            'extra_motion_main_section'
        );

        add_settings_field(
            'widget_motions_toggle',
            'Widget Motions',
            array($this, 'widget_motions_toggle_callback'),
            'extra_motion_settings',
            'extra_motion_main_section'
        );

        add_settings_field(
            'entrance_animations_toggle',
            'Entrance Animations',
            array($this, 'entrance_animations_toggle_callback'),
            'extra_motion_settings',
            'extra_motion_main_section'
        );
    }

    /**
     * Renders the settings page for the Extra Motion plugin.
     */
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Elementor Extra Motion Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('extra_motion_settings');
                do_settings_sections('extra_motion_settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Callback function to render the main settings section.
     */
    public function main_section_callback() {
        echo '<p>Configure the settings for Elementor Extra Motion plugin.</p>';
    }

    /**
     * Callback function to render the layout motions toggle field.
     */
    public function layout_motions_toggle_callback() {
        $options = get_option('extra_motion_options');
        $layout_motions_enabled = $options['layout_motions_enabled'] ?? false;
        $layout_motions_enabled = $layout_motions_enabled === 'on';

        ?>
        <label for="layout_motions_enabled">
            <input
                type="checkbox"
                id="layout_motions_enabled"
                name="extra_motion_options[layout_motions_enabled]"
                <?php checked($layout_motions_enabled, true); ?>
            />
            Enable all motion effects for sections and containers.
        </label>
        <?php
    }

    /**
     * Callback function to render the widget motions toggle field.
     */
    public function widget_motions_toggle_callback() {
        $options = get_option('extra_motion_options');
        $widget_motions_enabled = $options['widget_motions_enabled'] ?? false;
        $widget_motions_enabled = $widget_motions_enabled === 'on';

        ?>
        <label for="widget_motions_enabled">
            <input
                type="checkbox"
                id="widget_motions_enabled"
                name="extra_motion_options[widget_motions_enabled]"
                <?php checked($widget_motions_enabled, true); ?>
            />
            Enable all motion effects for all widgets.
        </label>
        <?php
    }

    /**
     * Callback function to render the entrance animations toggle field.
     */
    public function entrance_animations_toggle_callback() {
        $options = get_option('extra_motion_options');
        $entrance_animations_enabled = $options['entrance_animations_enabled'] ?? false;
        $entrance_animations_enabled = $entrance_animations_enabled === 'on';

        ?>
        <label for="entrance_animations_enabled">
            <input
                type="checkbox"
                id="entrance_animations_enabled"
                name="extra_motion_options[entrance_animations_enabled]"
                <?php checked($entrance_animations_enabled, true); ?>
            />
            Enable additional entrance animations.
        </label>
        <?php
    }

    /**
     * Callback function to add settings link in plugin action links.
     *
     * @param array $links An array of existing plugin action links.
     * @return array Modified array of plugin action links with added settings link.
     */
    public function add_settings_link($links) {
        $settings_link =
            '<a href="options-general.php?page=extra-motion-settings">'
            . 'Settings'
            . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
}

// Instantiate the Extra_Motion_Settings class
new Extra_Motion_Settings();

?>
