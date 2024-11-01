<?php
/*
 * Copyright (c) 2012-2017 Sur.ly
 * This file is part of Sur.ly WordPress plugin.
 * 
 * Sur.ly WordPress plugin plugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Sur.ly WordPress plugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Sur.ly WordPress plugin.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * Plugin Name: Sur.ly
 * Plugin URI: http://sur.ly
 * Description: Sur.ly enables you to control and analyze any outbound links published by your site visitors in user-generated content as well as to protect and retain users that follow such links.
 * Version: 3.0.3
 * Author: Sur.ly
 */

require_once implode(DIRECTORY_SEPARATOR, array('lib', 'SurlyWordPress.php'));

define('SURLY_TAB_SETTINGS_PLUGIN', 'settings-plugin');
define('SURLY_TAB_SETTINGS_TOOLBAR', 'settings-toolbar');

define('SURLY_PANEL_URL', 'https://surdotly.com/settings/');

function surly_admin_enqueue_scripts($hook)
{
    if ($hook != 'settings_page_surly') {
        return;
    }

    wp_enqueue_style('surly_wp_admin_css_fonts-googleapis', 'https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700,700i');
    wp_enqueue_style('surly_wp_admin_css_font-awesome', plugins_url('/static/css/font-awesome.min.css', __FILE__));
    wp_enqueue_style('surly_wp_admin_css_surly-style', plugins_url('/static/css/surly-style.css', __FILE__));
    wp_enqueue_style('surly_wp_admin_css_chosen', plugins_url('/static/css/chosen.css', __FILE__));

    wp_enqueue_script('surly_wp_admin_js_chosen', plugins_url('/static/js/chosen.jquery.min.js', __FILE__));
    wp_enqueue_script('surly_wp_admin_js_icheck', plugins_url('/static/js/jquery.icheck.min.js', __FILE__));
    wp_enqueue_script('surly_wp_admin_js_post_message', plugins_url('/static/js/jquery.postMessage.min.js', __FILE__));
    wp_enqueue_script('surly_wp_admin_js_surly-script', plugins_url('/static/js/surly-script.js', __FILE__));
}

function surly_admin_menu()
{
    add_options_page('Sur.ly', 'Sur.ly', 'manage_options', basename(__FILE__), 'surly_admin_settings');
}

function surly_admin_settings()
{
    function get_tabs()
    {
        return array(
            SURLY_TAB_SETTINGS_PLUGIN => array( 
                'label' => __('Sur.ly plugin settings'),
                'url' => esc_url(add_query_arg(array('page' => 'surly.php'), admin_url('options-general.php'))),
            ),
            SURLY_TAB_SETTINGS_TOOLBAR => array( 
                'label' => __('Toolbar settings'),
                'url' => esc_url(add_query_arg(array('page' => 'surly.php', 'tab' => SURLY_TAB_SETTINGS_TOOLBAR), admin_url('options-general.php'))),
            ),
        );
    }

    function get_active_tab()
    {
        $tab = isset($_GET['tab']) ? $_GET['tab'] : null;

        return isset(get_tabs()[$tab]) ? $tab : SURLY_TAB_SETTINGS_PLUGIN;
    }

    function get_tabs_html()
    {
        $result = '';

        foreach (get_tabs() as $tab_id => $tab) {
            $class = ($tab_id == get_active_tab()) ? ' nav-tab-active' : '';

            $result .= '<a href="' . $tab['url'] . '" class="nav-tab' . $class . '">' . esc_html($tab['label']) . '</a>';
        }

        return $result;
    }

    if (!get_option('surly_initial', false)) {
        echo '<div class="wrapper-surly"><div id="surly-message" class="updated notice notice-success is-dismissible" style="display:none"><p>Settings saved.</p></div></div>';
    }

    if (get_option('surly_initial', false)) {
        require_once 'surly-settings-initial.php';
    } else if (get_option('surly_toolbar_id', false)) {

        echo '<h2 class="nav-tab-wrapper">' . get_tabs_html() . '</h2>';

        require_once 'surly-' . get_active_tab() . '.php';
    } else {
        require_once 'surly-settings-short.php';
    }
}

function surly_get_roles()
{
    global $wp_roles;

    return $wp_roles->roles;
}

function surly_save_subdomain()
{
    $surly_subdomain = isset($_POST['surly_subdomain']) ? trim($_POST['surly_subdomain']) : '';

    if ($surly_subdomain != get_option('surly_subdomain', '')) {

        $surly = surly_get_sdk();

        if ($surly_subdomain == '') {

            $surly->trackHistory(SURLY_ACTION_TYPE_SUBDOMAIN_UNLINK);
        } else {

            $result = json_decode($surly->linkSubdomain($surly_subdomain), true);

            if (isset($result['error_code'])) {

                $errors = array(
                    1 => __('Invalid subdomain name: please check spelling and punctuation.'),
                    2 => __('Please use your website’s existing subdomain.'),
                    3 => __('Sorry, this subdomain is already in use.'),
                    4 => __('Incorrect CNAME record, please check your subdomain settings.'),
                    5 => __('An unexpected error occured, and we are sorry about it.'),
                );

                return isset($errors[$result['error_code']]) ? array('error' => $errors[$result['error_code']]) : array('error' => $errors[5]);
            }
            elseif (isset($result['subdomain'])) {
                $surly_subdomain = $result['subdomain'];

                $surly->trackHistory(SURLY_ACTION_TYPE_SUBDOMAIN_LINK);
            }
            else {
                $surly_subdomain = get_option('surly_subdomain', '');
            }
        }
    }

    update_option('surly_subdomain', $surly_subdomain);
}

function surly_save_trusted_groups()
{
    $surly_trusted_groups = isset($_POST['surly_trusted_groups']) ? (array) $_POST['surly_trusted_groups'] : array();

    $roles = surly_get_roles();

    foreach ($surly_trusted_groups as $key => $value) {
        if(!isset($roles[$value])){
            unset($surly_trusted_groups[$key]);
        }
    }

    update_option('surly_trusted_groups', $surly_trusted_groups);
}

function surly_save_shorten_urls()
{
    $surly_shorten_urls = !empty($_POST['surly_shorten_urls']);

    update_option('surly_shorten_urls', $surly_shorten_urls);
}

function surly_save_replace_urls()
{
    $surly_replace_urls = isset($_POST['surly_replace_urls']) ? (array) $_POST['surly_replace_urls'] : array(0);
    $surly_replace_urls = array_intersect(array(0), $surly_replace_urls) ?: array_intersect(array(1, 2, 3), $surly_replace_urls) ?: array(0);

    update_option('surly_replace_urls', $surly_replace_urls);
}

function surly_ajax_save_toolbar_settings()
{
    $surly_toolbar_id = isset($_POST['surly_toolbar_settings']['id']) ? $_POST['surly_toolbar_settings']['id'] : '';
    $surly_toolbar_password = isset($_POST['surly_toolbar_settings']['password']) ? $_POST['surly_toolbar_settings']['password'] : '';

    update_option('surly_toolbar_id', $surly_toolbar_id);
    update_option('surly_toolbar_password', $surly_toolbar_password);

    update_option('surly_initial', '2-1');

    $surly = surly_get_sdk();
    $surly->trackHistory(SURLY_ACTION_TYPE_AUTH);

    wp_die(1);
}

function surly_ajax_save_subdomain()
{
    $result = surly_save_subdomain();

    if (isset($result['error'])) {
        wp_send_json(array('error' => $result['error']));
    }

    update_option('surly_initial', '2-2');

    wp_die(1);
}

function surly_ajax_save_trusted_groups()
{
    surly_save_trusted_groups();

    update_option('surly_initial', '4-1');

    wp_die(1);
}

function surly_ajax_save_shorten_urls()
{
    surly_save_shorten_urls();

    update_option('surly_initial', '4-2');

    wp_die(1);
}

function surly_ajax_save_replace_urls()
{
    surly_save_replace_urls();

    update_option('surly_initial', false);

    $surly = surly_get_sdk();
    $surly->trackHistory(SURLY_ACTION_TYPE_ACTIVATION);

    wp_die(1);
}

function surly_ajax_skip_step()
{
    $steps = array('1-0', '2-1', '2-2', '3-0', '4-1', '4-2');

    $next_step = isset($_POST['next_step']) && in_array($_POST['next_step'], $steps) ? $_POST['next_step'] : $steps[0];

    update_option('surly_initial', $next_step);

    wp_die(1);
}

function surly_ajax_save_settings()
{
    surly_save_replace_urls();
    surly_save_shorten_urls();
    surly_save_trusted_groups();

    $result = surly_save_subdomain();

    if (isset($result['error'])) {
        wp_send_json(array('error' => $result['error']));
    }

    wp_die(1);
}

function surly_ajax_save_trusted_domain()
{
    $trusted_domain = isset($_POST['surly_trusted_domain'])? preg_replace('/^https?:\/\/(.+)/',"$1", strtolower(trim($_POST['surly_trusted_domain']))) : '';

    if (empty($trusted_domain)) {
        wp_send_json(array('error' => __('Invalid domain name: please check spelling and punctuation.')));
    }

    if (preg_match('!(?P<host>(?:[a-z0-9_-]+\.)+[a-z]+)!u', $trusted_domain, $result)) {
        $trusted_domain = $result['host'];
    } else {
        wp_send_json(array('error' => __('Invalid domain name: please check spelling and punctuation.')));
    }

    if (in_array($trusted_domain, get_option('surly_trusted_domains', array()))) {
        wp_send_json(array('error' => __('This domain is already in the list.')));
    }

    update_option('surly_trusted_domains',
        array_merge(get_option('surly_trusted_domains', array()), array($trusted_domain))
    );

    wp_send_json(array('domain' => $trusted_domain));
}

function surly_ajax_delete_trusted_domains()
{
    $trusted_domains = isset($_POST['surly_trusted_domains']) ? (array) $_POST['surly_trusted_domains'] : array();

    update_option('surly_trusted_domains',
        array_diff(get_option('surly_trusted_domains', array()), $trusted_domains)
    );

    wp_die(1);
}

function surly_activation()
{
    global $wpdb;

    if (!get_option('surly_activation_hash')) {
        update_option('surly_activation_hash', md5(time()));
    }

    $wpdb->query('CREATE TABLE IF NOT EXISTS `' . $wpdb->prefix . 'shortener_cache' . '` (
        `long_url` varchar(1000),
        `hash` binary(16) NOT NULL,
        `short_id` varchar(10),
        PRIMARY KEY (`hash`,  `long_url`(100))
        ) DEFAULT CHARSET=utf8;'
    );

    $surly = surly_get_sdk();
    $surly->trackHistory(SURLY_ACTION_TYPE_INSTALL);
}

function surly_deactivation()
{
    $surly = surly_get_sdk();
    $surly->trackHistory(SURLY_ACTION_TYPE_DEACTIVATION);
}

function surly_uninstall()
{
    global $wpdb;

    delete_option('surly_trusted_domains');
    delete_option('surly_trusted_groups');
    delete_option('surly_shorten_urls');
    delete_option('surly_toolbar_id');
    delete_option('surly_toolbar_password');
    delete_option('surly_subdomain');
    delete_option('surly_replace_urls');
    delete_option('surly_initial');
    delete_option('surly_activation_hash');

    $wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . 'shortener_cache' . '`');

    $surly = surly_get_sdk();
    $surly->trackHistory(SURLY_ACTION_TYPE_UNINSTALL);
}

function surly_get_sdk()
{
    static $surly;

    if (!isset($surly)) {
        $surly = new SurlyWordPress(get_option('surly_toolbar_id', SURLY_DEFAULT_TOOLBAR_ID), get_option('surly_shorten_urls', false));

        if (get_option('surly_subdomain')) {
            $surly->setPanelHost(get_option('surly_subdomain'));
        }

        foreach (get_option('surly_trusted_groups', array()) as $group) {
            $surly->whitelistGroups($group);
        }

        foreach (get_option('surly_trusted_domains', array()) as $domain) {
            $surly->whitelist($domain);
        }

        $site_url = parse_url(get_bloginfo('url'));
        $surly->whitelist($site_url['host']);
    }

    return $surly;
}

function surly_replace($content, $userId = null)
{
    $surly = surly_get_sdk();

    return ($userId && $surly->isWhitelistedUser($userId)) ? $content : $surly->process($content);
}

function surly_replace_all()
{
    if ( !is_admin() ) {
        ob_start('surly_replace');
    }
}

function surly_replace_in_content($content)
{
    global $post;

    return surly_replace($content, $post->post_author);
}

function surly_replace_in_comment($content)
{
    global $comment;

    return surly_replace($content, $comment->user_id);
}

function surly_admin_notice()
{
    if (in_array(0, get_option('surly_replace_urls', array(0)))) {
        echo '<div class="update-nag">Outbound links on your site are not yet accompanied by Sur.ly. <a href="' . esc_url(add_query_arg(array('page' => 'surly.php'), admin_url('options-general.php'))) . '">Enable safe outbound linking now</a>.</div>';
    }
}

register_activation_hook(__FILE__, 'surly_activation');
register_deactivation_hook(__FILE__, 'surly_deactivation');
register_uninstall_hook(__FILE__, 'surly_uninstall');

add_action('admin_enqueue_scripts', 'surly_admin_enqueue_scripts');
add_action('admin_menu', 'surly_admin_menu');
add_action('admin_notices', 'surly_admin_notice');
add_action('wp_ajax_surly_save_settings', 'surly_ajax_save_settings');
add_action('wp_ajax_surly_save_toolbar_settings', 'surly_ajax_save_toolbar_settings');
add_action('wp_ajax_surly_save_subdomain', 'surly_ajax_save_subdomain');
add_action('wp_ajax_surly_save_trusted_domain', 'surly_ajax_save_trusted_domain');
add_action('wp_ajax_surly_delete_trusted_domains', 'surly_ajax_delete_trusted_domains');
add_action('wp_ajax_surly_save_trusted_groups', 'surly_ajax_save_trusted_groups');
add_action('wp_ajax_surly_save_shorten_urls', 'surly_ajax_save_shorten_urls');
add_action('wp_ajax_surly_save_replace_urls', 'surly_ajax_save_replace_urls');
add_action('wp_ajax_surly_skip_step', 'surly_ajax_skip_step');

if (in_array(3, get_option('surly_replace_urls', array(0)))) {
    add_action('wp_loaded', 'surly_replace_all', 9999);
} else {
    if (in_array(1, get_option('surly_replace_urls', array(0)))) {
        add_filter('the_content', 'surly_replace_in_content', 9999);
        add_filter('the_excerpt', 'surly_replace_in_content', 9999);
    }

    if (in_array(2, get_option('surly_replace_urls', array(0)))) {
        add_filter('comment_text', 'surly_replace_in_comment', 9999);
        add_filter('comment_excerpt', 'surly_replace_in_comment', 9999);
        add_filter('get_comment_author_link', 'surly_replace_in_comment', 9999);
    }
}