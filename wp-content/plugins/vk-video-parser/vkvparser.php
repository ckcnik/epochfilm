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
    register_setting( 'vkvp-settings-group', 'vkvp_imdb_login', 'strval' );
    register_setting( 'vkvp-settings-group', 'vkvp_imdb_password', 'strval' );
}

if (!function_exists('mb_ucfirst') && extension_loaded('mbstring')) {
    function mb_ucfirst($str, $encoding = 'UTF-8') {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);
        return $str;
    }
}

function rus2translit($string) {
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => "'",   'ы' => 'y',   'ъ' => "'",
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => "'",   'Ы' => 'Y',   'Ъ' => "'",
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya', ' ' => '-',
    );
    return strtr($string, $converter);
}

function getCategoryId($catName, $parentCat = 1) {
    $catId = get_cat_ID($catName);
    if (!$catId) {
        $catId = wp_insert_category(array(
            'cat_name' => $catName,
            'category_nicename' => rus2translit(strtolower($catName)),
            'category_parent' => $parentCat,
        ));
    }

    return $catId;
}