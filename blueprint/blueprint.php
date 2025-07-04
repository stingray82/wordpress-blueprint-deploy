<?php
/*
Plugin Name: Install Blueprint - Configurable Theme Installer
Description: Installs a theme (from repo or ZIP) and plugins automatically.
Version: 1.0
Author: Stingray82
*/

defined('ABSPATH') || exit;

require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/theme.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Needed for plugins_api()

register_activation_hook(__FILE__, 'ibp_run_install_blueprint');

// === CONFIGURATION START ===
const IBP_THEME_REPO_SLUG = 'kadence'; // Set to null if using ZIP
const IBP_THEME_ZIP_URL = ''; // e.g., 'https://example.com/my-theme.zip'
const IBP_THEME_SLUG_FOR_ZIP = ''; // Set if using ZIP


const IBP_PLUGIN_REPO_SLUGS = [
    'surecart',
    'sureforms',
];

const IBP_PLUGIN_ZIP_URLS = [
    'https://github.com/stingray82/WP-Tasks-After-Install/releases/latest/download/WP-Tasks-After-Install.zip',

];
// === CONFIGURATION END ===

function ibp_run_install_blueprint() {
    // Install Theme
    if (IBP_THEME_ZIP_URL) {
        ibp_install_theme_from_zip(IBP_THEME_ZIP_URL);
    } elseif (IBP_THEME_REPO_SLUG) {
        ibp_install_theme_from_repo(IBP_THEME_REPO_SLUG);
    }

    // Install Plugins from repo
    foreach (IBP_PLUGIN_REPO_SLUGS as $slug) {
        ibp_install_plugin_from_repo($slug);
    }

    // Install Plugins from ZIPs
    foreach (IBP_PLUGIN_ZIP_URLS as $zip_url) {
        ibp_install_plugin_from_zip($zip_url);
    }

    // Finished
    ibp_deactivate_and_delete_plugin();
}

function ibp_install_theme_from_repo($slug) {
    if (!wp_get_theme($slug)->exists()) {
        $upgrader = new Theme_Upgrader(new Automatic_Upgrader_Skin());
        $upgrader->install("https://downloads.wordpress.org/theme/{$slug}.latest-stable.zip");
    }
    switch_theme($slug);
}

function ibp_install_theme_from_zip($url) {
    $upgrader = new Theme_Upgrader(new Automatic_Upgrader_Skin());
    $result = $upgrader->install($url);
    if (is_wp_error($result)) return;

    // Use manually defined slug for switching
    if (defined('IBP_THEME_SLUG_FOR_ZIP') && IBP_THEME_SLUG_FOR_ZIP) {
        switch_theme(IBP_THEME_SLUG_FOR_ZIP);
    }
}


function ibp_install_plugin_from_repo($slug) {
    $api = plugins_api('plugin_information', ['slug' => $slug, 'fields' => ['sections' => false]]);
    if (is_wp_error($api)) return;

    $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
    $result = $upgrader->install($api->download_link);

    if (!is_wp_error($result)) {
        activate_plugin("{$slug}/{$slug}.php");
    }
}

function ibp_install_plugin_from_zip($url) {
    $upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
    $result = $upgrader->install($url);

    if (is_wp_error($result)) return;

    // Find the main plugin file from the newly installed directory
    $plugin_dir = basename($upgrader->result['destination']);

    if (!$plugin_dir) return;

    $plugin_files = glob(WP_PLUGIN_DIR . "/$plugin_dir/*.php");
    foreach ($plugin_files as $file) {
        $plugin_data = get_plugin_data($file, false, false);
        if (!empty($plugin_data['Name'])) {
            $relative_path = plugin_basename($file);
            activate_plugin($relative_path);
            break;
        }
    }
}

function ibp_deactivate_and_delete_plugin() {
    $plugin_file = plugin_basename(__FILE__);

    deactivate_plugins($plugin_file);

    // Delay delete using shutdown hook so it's not in the same request
    add_action('shutdown', function () use ($plugin_file) {
        delete_plugins([$plugin_file]);
    });
}