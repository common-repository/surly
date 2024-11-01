<?php

require_once implode(DIRECTORY_SEPARATOR, array('surly-sdk-php', 'surly', 'Surly.php'));

define('SURLY_ACTION_TYPE_INSTALL', 1);
define('SURLY_ACTION_TYPE_AUTH', 2);
define('SURLY_ACTION_TYPE_ACTIVATION', 3);
define('SURLY_ACTION_TYPE_DEACTIVATION', 4);
define('SURLY_ACTION_TYPE_UNINSTALL', 5);
define('SURLY_ACTION_TYPE_SUBDOMAIN_LINK', 6);
define('SURLY_ACTION_TYPE_SUBDOMAIN_UNLINK', 7);

class SurlyWordPress extends Surly
{
    var $rootStatusKey = 'surly_root_status';
    var $whitelistGroups = array();
    var $isRootDomainAlive = null;

    function hashLongUrl($longUrl)
    {
        $hash = md5($longUrl);

        // Manually translate to binary form to support PHP 4.3
        $result = '';
        for ($i = 0; $i < 32; $i+=2) {
          $digits = substr($hash, $i, 2);
          $number = hexdec($digits);
          $result.=chr($number);
        }

        return $result;
    }

    /**
     * Adds a user to processing whitelist. Links to whitelisted users won't be processed
     *
     * @public
     * @param int $userId
     * @return Surly
     */ 
    function whitelistGroups($group)
    {
        if ($group) {
            $this->whitelistGroups[] = $group;
        }

        return $this;
    }

    /**
     * Check whether user is in whitelist or not
     *
     * @param string $user
     * @return bool
     */
    function isWhitelistedUser($userId)
    {
        $user = new WP_User( $userId );

        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            if ( array_intersect($user->roles, $this->whitelistGroups) ) {
                return true;
            }            
        }

        return false;
    }

    function cacheShortIds($url2shortIds)
    {
        global $wpdb;

        if (!$url2shortIds) {
            return;
        }

        $insert = array();

        foreach ($url2shortIds as $longUrl => $shortId)
        {
            $hash = $this->hashLongUrl($longUrl);

            $insert[] =  "('" . $wpdb->_real_escape($longUrl) . "','" . $wpdb->_real_escape($hash) . "','" . $wpdb->_real_escape($shortId) . "')";
        }

        $wpdb->query("INSERT IGNORE INTO `" . $wpdb->prefix . 'shortener_cache' . "` (`long_url`,`hash`,`short_id`) VALUES " . implode(',',$insert) . "");
    }

    function getCachedShortIds($urls)
    {
        global $wpdb;

        if (!$urls) {
            array();
        }
        
        $where = array();
        $result = array();

        foreach ($urls as $longUrl) {
            $hash = $this->hashLongUrl($longUrl);
            $where[] = "(`hash` = '" . $wpdb->_real_escape($hash) . "' AND `long_url` = '" . $wpdb->_real_escape($longUrl) . "')";
        }

        $res = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . 'shortener_cache' . "` WHERE " . implode(' OR ', $where));

        foreach ($res as $r) {
            $result[$r->long_url] = $r->short_id;
        }

        return $result;
    }

    function getCachedRootStatus()
    {
        return get_option($this->rootStatusKey);
    }

    function cacheRootStatus($rootStatus)
    {
        update_option($this->rootStatusKey, $rootStatus);
    }

    function trackHistory($actionType)
    {
        $this->timeout = SURLY_API_TRACK_TIMEOUT;

        $this->_performRequest(
            $this->apiHost . SURLY_API_TRACK_HISTORY_PATH,  'POST',
                array(
                    'action_type' => $actionType,
                    'site_url' => get_bloginfo('url'),
                    'toolbar_id' => get_option('surly_toolbar_id', null),
                    'hash' => get_option('surly_activation_hash'),
                )
        );

        $this->timeout = SURLY_API_TIMEOUT;
    }

    function linkSubdomain($subdomain)
    {
        return $this->_performRequest(
            $this->apiHost . SURLY_API_SUBDOMAIN_LINK, 'POST',
                array(
                    'toolbar_id' => get_option('surly_toolbar_id'),
                    'password' => get_option('surly_toolbar_password'),
                    'subdomain' => $subdomain,
                )
        );
    }
}