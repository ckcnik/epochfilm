<?php
/*
Plugin Name: VK Video Parser
Description: Плагин парсит видео из VK
Version: 1.0
*/

define('VKVP_INDEX_TITLE', __('VK Video Parser'));
define('VKVP_SHORT_TITLE', __('VKV Parser'));
define('VKVP_SETTINGS_TITLE', __('Settings'));

add_action('admin_menu', 'vkvp_init');

function vkvp_init () {
    add_menu_page(VKVP_INDEX_TITLE, VKVP_SHORT_TITLE, 'level_8', __DIR__ . '/vkvp_index.php');
    add_submenu_page(__DIR__ . '/vkvp_index.php', VKVP_INDEX_TITLE . ' - ' . VKVP_SETTINGS_TITLE, VKVP_SETTINGS_TITLE, 'level_8', __DIR__ . '/vkvp_settings.php');

    add_action( 'admin_init', 'vkvp_register_settings' );
}

function vkvp_register_settings() {
    register_setting( 'vkvp-settings-group', 'vkvp_client_id', 'intval' );
    register_setting( 'vkvp-settings-group', 'vkvp_client_secret', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_access_token', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_expires_in', 'intval' );
    register_setting( 'vkvp-settings-group', 'vkvp_kinopisk_login', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_kinopisk_password', 'strval' );
}