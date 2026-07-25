<?php

/**
 * Class Plugin_Activation_Handler
 *
 * Handles plugin activation and checks for required dependencies.
 */
class Plugin_Activation_Handler {

    /**
     * Plugin_Activation_Handler constructor.
     *
     * Adds action hook to check for required dependencies on admin init.
     */
    public function __construct() {
        add_action('admin_init', array($this, 'check_dependencies_on_admin_init'));
    }

    /**
     * Checks for required dependencies on admin init.
     *
     * Deactivates the plugin and displays a notice if required dependencies are not active.
     */
    public function check_dependencies_on_admin_init() {
        if (!is_plugin_active('elementor/elementor.php')) {
            deactivate_plugins(EXTRA_MOTION_PLUGIN_FILE);
            add_action('admin_notices', array($this, 'required_dependencies_notice'));
        }
    }

    /**
     * Displays a notice if required dependencies are not active.
     */
    public function required_dependencies_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php
                echo esc_html__(ELEMENTOR_REQUIRED_NOTICE, 'extra-motion');
            ?></p>
        </div>
        <?php
    }
}

// Instantiate the class
new Plugin_Activation_Handler();

?>