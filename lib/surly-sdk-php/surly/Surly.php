<?php
/*
 * Copyright (c) 2012-2017 Sur.ly
 * This file is part of Sur.ly SDK.
 *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU Lesser General Public License as        *
 *   published by the Free Software Foundation; either version 3 of the    *
 *   License, or (at your option) any later version.                       *
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Sur.ly SDK.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!defined('SURLY_LOADED')) {
	define('SURLY_LOADED', true);
	define('SURLY_VERSION', '2.5.0');
	define('SURLY_API_HOST', 'surdotly.com');
	define('SURLY_API_ROOT_STATUS_PATH', '/root_status');
	define('SURLY_API_PATH', '/shorten');
	define('SURLY_API_TRACK_HISTORY_PATH', '/track/history/');
	define('SURLY_API_SUBDOMAIN_LINK', '/analytics/subdomainLink');
	define('SURLY_API_IFRAME_LOGIN', '/iframe_login/');
	define('SURLY_PANEL_HOST', 'sur.ly');
	define('SURLY_BACKUP_PANEL_HOST', 'surdotly.com');
	define('SURLY_API_TIMEOUT', '0.6');
	define('SURLY_API_TRACK_TIMEOUT', 2);
	define('SURLY_DEFAULT_TOOLBAR_ID', 'AA000015');

	/**
	 * @see examples/basic.php
	 * @copyright Sur.ly
	 */
	class Surly {

		var $whitelist = array(SURLY_PANEL_HOST, SURLY_BACKUP_PANEL_HOST);
		var $toolbarId = SURLY_DEFAULT_TOOLBAR_ID;
		var $useShortener = false;
		var $shortenerCache = array();
		var $timeout = SURLY_API_TIMEOUT;
		var $apiHost = SURLY_API_HOST;
		var $apiPath = SURLY_API_PATH;
		var $apiRootStatusPath = SURLY_API_ROOT_STATUS_PATH;
		var $panelHost = SURLY_PANEL_HOST;
		var $isRootDomainAlive = true;
		var $useCustomPanel = false;

		/**
		 * Initialize object with toolbar_id and shortener use
		 *
		 * Note: not recommended to use shortener without caching enabled
		 *
		 * @public
		 * @param $toolbar_id
		 * @param bool $use_shortener
		 * @return self
		 */
		function __construct($toolbar_id = SURLY_DEFAULT_TOOLBAR_ID, $use_shortener = false)
		{
			if ($toolbar_id && is_string($toolbar_id)) {
				$this->toolbarId = $toolbar_id;
			}

			if ($use_shortener) {
				$this->_enableShortener();
			}

			return $this;
		}

		/**
		 * Adds a domain to processing whitelist. Links to whitelisted domains won't be processed
		 *
		 * @public
		 * @param string $domain
		 * @return self
		 */
		function whitelist($domain)
		{
			if ($domain) {
				$this->whitelist[] = strtolower(preg_replace('/^(https?:\/\/)?(www\.)?/', '', $domain));
			}

			return $this;
		}

		/**
		 * Processes an HTML string. Replaces all found links (within <A href>) with links
		 * pointing to Sur.ly interstitial page according to initialization parameters
		 *
		 * @public
		 * @param string $html
		 * @return string
		 */
		function process($html)
		{
			if (!$this->isRootDomainAlive()) {
				$this->useBackupDomain();
			}

			// if shortener enabled - match them all and shorten in batch
			if ($this->useShortener) {
				// preg_match_all
				preg_match_all('|(<\s*a[^>]*\s+href=(["\']?))https?://([^\s"\'>\.]+\.[^\s"\'>]+)|si', $html, $m, PREG_SET_ORDER);

				$links = array();
				foreach ($m as $href) {
					$link = $href[3];

					$linkComponents = parse_url("http://" . $link);
					$host = strtolower($linkComponents['host']);

					if (!$this->_isWhitelisted($host)){
						$link = htmlspecialchars_decode($link, ENT_QUOTES);
						// It always add missed / after domain name (http://example.com#abc -> http://example.com/#abc)
						$link = preg_replace('/^(' . $host . ')#(.*)$/i', '$1/#$2', $link);

						$links[] = $link;
					}
				}
				$this->_shorten($links);
			}

			return preg_replace_callback(
				'|(<\s*a[^>]*\s+href=(["\']?))(https?://)([^\s"\'>\.]+\.[^\s"\'>]+)([^>]+)|si',
				array($this, '_processCallback'),
				$html
			);
		}

		/**
		 * Processes an url. Replaces it with url pointing to Sur.ly interstitial page
		 * according to initialization parameters
		 *
		 * @public
		 * @param string $url
		 * @return null|string
		 */
		function processUrl($url)
		{
			if (!$this->isRootDomainAlive()) {
				$this->useBackupDomain();
			}

			if (!preg_match('|^(https?://)([^\s"\'>\.]+\..+)$|i', $url, $matches)) {
				return $url;
			}

			list(,$scheme, $link) = $matches;

			$linkComponents = parse_url("http://" . $link);
			$host = strtolower($linkComponents['host']);

			if ($this->_isWhitelisted($host)) {
				return $url;
			}

			$link = htmlspecialchars_decode($link, ENT_QUOTES);
			// It always add missed / after domain name (http://example.com#abc -> http://example.com/#abc)
			$link = preg_replace('/^(' . $host . ')#(.*)$/i', '$1/#$2', $link);

			$trailingSlash = false;

			if ($host == rtrim(strtolower($link), '/')) {
				$trailingSlash = true;
			}

			if ($this->useShortener) {
				$this->_shorten(array($link));
				$normalizedLink = $this->_normalizeUrl($link);

				if (isset($this->shortenerCache[$normalizedLink])) {
					$link = $this->shortenerCache[$normalizedLink];
				}
			}

			$link = $this->getPrefix($scheme) . $this->_urlEncode($link);

			if ($this->useCustomPanel) {
				$scheme = 'http://';

				if ($trailingSlash) {
					$link .= '/';
				}
			}
			else if ($this->toolbarId) {
				$link .=  '/' . $this->toolbarId;
			}

			return $scheme . $this->panelHost . "/${link}";
		}

		/**
		 * Processes array of urls. Replaces them with urls pointing to Sur.ly interstitial page
		 * according to initialization parameters. Return urls in exactly the same order as receive
		 *
		 * @public
		 * @param array $urls
		 * @return array
		 */
		function processMultipleUrls(array $urls)
		{
			if (!$this->isRootDomainAlive()) {
				$this->useBackupDomain();
			}

			$processedUrls = array();
			$processingUrlsWithScheme = array();
			$rightOrder = array_keys($urls);
			$links = array();

			foreach ($urls as $key => $url) {
				if (!preg_match('|^(https?://)([^\s"\'>\.]+\..+)$|i', $url, $matches)) {
					$processedUrls[$key] = $url;
					unset($urls[$key]);
					continue;
				}

				list(,$scheme, $link) = $matches;
				$linkComponents = parse_url("http://" . $link);
				$host = strtolower($linkComponents['host']);

				if ($this->_isWhitelisted($host)) {
					$processedUrls[$key] = $url;
					unset($urls[$key]);
					continue;
				}

				$link = htmlspecialchars_decode($link, ENT_QUOTES);
				// It always add missed / after domain name (http://example.com#abc -> http://example.com/#abc)
				$link = preg_replace('/^(' . $host . ')#(.*)$/i', '$1/#$2', $link);

				$trailingSlash = false;

				if ($host == rtrim(strtolower($link), '/')) {
					$trailingSlash = true;
				}

				$processingUrlsWithScheme[$key] = array(
					'scheme' => $scheme,
					'link' => $link,
					'trailingSlash' => $trailingSlash
				);

				$links[] = $link;
			}

			if ($this->useShortener) {

				$this->_shorten($links);

				foreach ($processingUrlsWithScheme as $key => $linkWithScheme) {

					$normalizedLink = $this->_normalizeUrl($linkWithScheme['link']);

					if (isset($this->shortenerCache[$normalizedLink])) {
						$processingUrlsWithScheme[$key]['link'] = $this->shortenerCache[$normalizedLink];
					}
				}
			}
			unset($urls);

			foreach ($processingUrlsWithScheme as $key => $linkWithScheme) {

				$link =  $this->getPrefix($linkWithScheme['scheme']) . $this->_urlEncode($linkWithScheme['link']);

				if ($this->useCustomPanel) {
					$linkWithScheme['scheme'] = 'http://';

					if ($linkWithScheme['trailingSlash']) {
						$link .= '/';
					}
				}
				else if ($this->toolbarId) {
					$link .= '/' . $this->toolbarId;
				}

				$link = $linkWithScheme['scheme'] . $this->panelHost . "/${link}";

				$processedUrls[$key] = $link;
				unset($processingUrlsWithScheme[$key]);
			}

			$processedOrderedList = array();
			foreach ($rightOrder as $key) {
				$processedOrderedList[$key] = $processedUrls[$key];
				unset($processedUrls[$key]);
			}

			return $processedOrderedList;
		}

		/**
		 * Checks the status of the main domain sur.ly
		 *
		 * Also handles caching logic on object-level and db-level
		 *
		 * @public
		 * @return bool
		 */
		function isRootDomainAlive()
		{
			if ($this->useCustomPanel) {
				$this->isRootDomainAlive = true;
			}
			else if ($this->isRootDomainAlive === null) {

				if (!$this->_canPerformHttpRequests()) {
					$this->isRootDomainAlive = true;
					return $this->isRootDomainAlive;
				}

				$rootDomainAliveInfo = $this->getCachedRootStatus();

				if ($rootDomainAliveInfo) {
					$rootDomainAliveInfo = @unserialize($rootDomainAliveInfo);
				}

				if (
					!$rootDomainAliveInfo
					|| $rootDomainAliveInfo['last_check'] < strtotime('-1 day')
				) {
					$this->isRootDomainAlive = $this->_checkIsRootDomainAliveRemotely();

					$this->cacheRootStatus(serialize(array(
						'last_check' => time(),
						'is_alive' => $this->isRootDomainAlive
					)));
				}
				else {
					$this->isRootDomainAlive = $rootDomainAliveInfo['is_alive'];
				}

			}

			return $this->isRootDomainAlive;
		}

		/**
		 * smart url encode
		 *
		 * @param $url
		 * @return string
		 */
		function _urlEncode($url)
		{
			if (preg_match('|^(https?://)?(www\.)?([^/]+/?)(.*)?$|i', $url, $matches)) {

				unset($matches[0]);
				unset($matches[1]);
				if ($matches[3]) {
					unset($matches[2]);
				}
				if ($matches[4] == '') {
					$matches[3] = rtrim($matches[3], '/');
				}
				$matches[4] = rawurlencode($matches[4]);
				$url = implode($matches);
			}
			else {
				$url = rawurlencode($url);
			}

			return $url;
		}

		/**
		 * Forces Sur.ly to use backup domain surdotly.com instead of sur.ly for url replacement
		 *
		 * @public
		 */
		function useBackupDomain()
		{
			$this->panelHost = SURLY_BACKUP_PANEL_HOST;
		}

		/**
		 * Set subdomain
		 *
		 * Note: It is necessarily needed to link subdomain to the toolbarld otherwise will be used standard one
		 *
		 * @public
		 */
		function setPanelHost($panelHost)
		{
			$panelHost = strtolower(preg_replace('/^(https?:\/\/)?(www\.)?/', '', $panelHost));

			$this->whitelist($panelHost);
			$this->panelHost = $panelHost;
			$this->useCustomPanel = true;

			return $this;
		}

		function getPrefix($scheme) {
			if ($this->useCustomPanel) {
				return $scheme == 'https://' ? 's/' : '';
			} else {
				return 'o/';
			}
		}

		/**
		 * normalize url
		 *
		 * @param $url
		 * @return string
		 */
		function _normalizeUrl($url)
		{
			$url = preg_replace('/^www\./', '', $url);

			return $url;
		}

		/**
		 * Enables shortener service
		 *
		 * @return self
		 */
		function _enableShortener()
		{
			if ($this->_canPerformHttpRequests()) {
				$this->useShortener = true;
			}

			return $this;
		}

		/**
		 * Performs the call to status API
		 *
		 * @return bool
		 */
		function _checkIsRootDomainAliveRemotely()
		{
			$rootStatusUrl = $this->apiHost . $this->apiRootStatusPath;
			$response = $this->_performRequest($rootStatusUrl);

			return $response != 'BAD';
		}

		/**
		 * Callback processor, called on each link from <A href> found
		 *
		 * @param array $m
		 * @return string
		 */
		function _processCallback($m)
		{
			list(,$prefix,,$scheme,$link,$suffix) = $m;

			$linkComponents = parse_url("http://" . $link);

			$host = strtolower($linkComponents['host']);

			if ($this->_isWhitelisted($host)) {
				return $prefix . $scheme . $link . $suffix;
			}

			$link = htmlspecialchars_decode($link, ENT_QUOTES);
			// It always add missed / after domain name (http://example.com#abc -> http://example.com/#abc)
			$link = preg_replace('/^(' . $host . ')#(.*)$/i', '$1/#$2', $link);

			$trailingSlash = false;

			if ($host == rtrim(strtolower($link), '/')) {
				$trailingSlash = true;
			}

			$normalizedLink = $this->_normalizeUrl($link);

			if ($this->useShortener && isset($this->shortenerCache[$normalizedLink]))
				$link = $this->shortenerCache[$normalizedLink];


			$link = $this->getPrefix($scheme) . $this->_urlEncode($link);

			if ($this->useCustomPanel) {
				$scheme = 'http://';

				if ($trailingSlash) {
					$link .= '/';
				}
			}
			else if ($this->toolbarId) {
				$link .= '/' . $this->toolbarId;
			}

			$replacedLink = $prefix . $scheme . $this->panelHost . "/${link}" . $suffix;

			// delete rel="nofollow"
			return preg_replace_callback(
				'/(\srel=)([\'"][^\'"]*[\'"])/si',
				array($this, '_removeNofollowCallback'),
				$replacedLink
			);
		}


		/**
		 * Callback processor, called on each link to delete nofollow in A tag
		 *
		 * @param array $m
		 * @return string
		 */
		function _removeNofollowCallback($m)
		{
			list(,$relTag, $relValue) = $m;

			if (preg_match("/^[\"']nofollow[\"']$/si", $relValue)) {
				// if rel='nofollow' we return empty string to delete empty rel tag like rel=""
				return '';
			}
			else {
				// else we trying to delete only nofollow in rel
				return $relTag . preg_replace("/^([\"'][^\"']*)(\bnofollow\b)([^\"']*[\"'])$/si", "$1$3", $relValue);
			}
		}

		/**
		 * Check whether domain is in whitelist or not
		 *
		 * @param string $domain
		 * @return bool
		 */
		function _isWhitelisted($domain)
		{
			foreach ($this->whitelist as $whitelistedDomain) {
				if (
					$whitelistedDomain == $domain
					|| '.' . $whitelistedDomain == substr($domain, -strlen($whitelistedDomain) - 1)
				) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Caches shortlink identifiers taken from Sur.ly shortener service into the local store.
		 *
		 * This method accepts an associative array containing a key-value pairs of target URLs
		 * and their corresponding IDs to be stored in cache (either memcached or any RDBMS).
		 *
		 * @protected
		 * @virtual
		 * @param array $url2shortIds
		 * @return void
		 */
		function cacheShortIds($url2shortIds)
		{}

		/**
		 * Gets the shortlink identifiers from the local store.
		 *
		 * This method accepts a collection of target URLs (without "http://www" prefix)
		 *  and returns an associative array containing a key-value pairs
		 * of target URLs and their corresponding IDs found in cache.
		 *
		 * @protected
		 * @virtual
		 * @param array $urls
		 * @return array
		 */
		function getCachedShortIds($urls)
		{
			return array();
		}

		/**
		 * Gets service status from the local store
		 *
		 * You should override this method in your subclass.
		 *
		 * It should retrieve cached service status from local store, that was saved
		 * there by cacheRootDomainAliveInfo method.
		 *
		 * @protected
		 * @virtual
		 * @return bool
		 */
		function getCachedRootStatus()
		{
			return false;
		}

		/**
		 * Caches service status into the local store
		 *
		 * You should override this method in your subclass.
		 *
		 * It should cache received string value into the local store like DB or Memcache
		 *
		 * @protected
		 * @virtual
		 * @param string $rootDomainAliveInfo
		 */
		function cacheRootStatus($rootDomainAliveInfo)
		{

		}

		/**
		 * Shortener caller. Shortens those urls that are missing from the local cache, and put them to the cache
		 *
		 * @param array $urls
		 * @return void
		 */
		function _shorten($urls)
		{
			foreach ($urls as $key => $url) {
				$url = $this->_normalizeUrl($url);

				if (!$url) {
					unset($urls[$key]);
				} else {
					$urls[$key] = $url;
				}
			}

			// remote those that are in object cache
			$urls = array_diff($urls, array_keys($this->shortenerCache));

			// get from the local store
			$this->shortenerCache = array_merge($this->shortenerCache, $this->getCachedShortIds($urls));

			// get from the remote store and put to the local store
			$urls = array_diff($urls, array_keys($this->shortenerCache));
			$remoteShortIds = $this->_shortenRemotely($urls);

			$this->shortenerCache = array_merge($this->shortenerCache, $remoteShortIds);
			$this->cacheShortIds($remoteShortIds);
		}

		/**
		 * Checks whether shortener service can be used
		 *
		 * @return bool
		 */
		function isShortenerAvailable()
		{
			return $this->_canPerformHttpRequests();
		}

		/**
		 * Checks whether current php installation can perform http requests
		 *
		 * @return bool
		 */
		function _canPerformHttpRequests()
		{
			return
				   (
						function_exists('curl_init')
				)
				|| (
					   function_exists('stream_context_create')
					&& ini_get('allow_url_fopen')
				)
				|| (
					   function_exists('fsockopen')
					&& false === strstr(ini_get('disable_functions'), 'fsockopen')
			   );
		}

		/**
		 * Calls a shortener service and returns an associative array containing a key-value pairs
		 * of target URLs and their corresponding IDs
		 *
		 * @param $urls
		 * @return mixed
		 */
		function _shortenRemotely($urls)
		{
			if (empty($urls)) return array();
			$urls = array_unique($urls);

			$response = $this->_callShortenerService($urls);
			return $response['urls'];
		}

		function _buildQuery($params) {
			$queryParts = array();

			foreach ($params as $name => $value) {
				if (is_array($value)) {
					foreach ($value as $key => $val) {
						$queryParts[] = $name . '[' . $key . ']'   . '=' . urlencode($val);
					}
				} else {
					$queryParts[] = $name . '=' . urlencode($value);
				}
			}

			return implode('&', $queryParts);
		}

		/**
		 * Perform HTTP-request via cURL
		 *
		 * @param $url
		 * @param string $method GET or POST
		 * @param array $params
		 * @return string
		 */
		function _performCurlRequest($url, $method = 'GET', $params = null)
		{
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "http://{$url}");
			curl_setopt($ch, CURLOPT_USERAGENT, $this->getSurlyApiUseragent());
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			if ($method == 'POST') {
				curl_setopt($ch, CURLOPT_POST, true);

				if ($params) {
					$postQuery = $this->_buildQuery($params);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $postQuery);
				}

			}

			// timouts
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, ceil($this->timeout));
			curl_setopt($ch, CURLOPT_TIMEOUT, ceil($this->timeout));

			$result = curl_exec($ch);
			curl_close ($ch);

			$result = trim($result);

			return $result;
		}

		/**
		 * Splits url to Host and Path
		 *
		 * @param $url
		 * @return array array($host, $path)
		 */
		function _splitHostAndPath($url)
		{
			$slashPosition = strpos($url, '/');

			if ($slashPosition === false) {
				return array($url, '/');
			}

			$host = substr($url, 0, $slashPosition);
			$path = substr($url, $slashPosition);

			return array($host, $path);
		}

		/**
		 * Perform HTTP-request via socket
		 *
		 * @param $url
		 * @param string $method GET or POST
		 * @param array $params
		 * @return string
		 */
		function _performSocketRequest($url, $method = 'GET', $params = null)
		{
			list($host, $path) = $this->_splitHostAndPath($url);

			$request = array(
				$method . " " . $path ." HTTP/1.0",
				"Host: " . $host,
			);

			if ($method == 'POST' && $params) {
				$postData = $this->_buildQuery($params);

				$request[] = "Content-type: application/x-www-form-urlencoded";
				$request[] = "Content-length: " . strlen($postData);
				$request[] = "User-agent: " . $this->getSurlyApiUseragent();
				$request[] = "Connection: close";
				$request[] = "";
				$request[] = $postData;
			}
			else {
				$request[] = "User-agent: " . $this->getSurlyApiUseragent();
				$request[] = "Connection: close";
				$request[] = "";
				$request[] = "";
			}

			$request = join("\r\n", $request);

			$socket = @fsockopen($this->apiHost, 80, $errno, $errstr, $this->timeout);
			if ($socket) {
				stream_set_timeout($socket, 0, $this->timeout * 1000000 /*microseconds*/);
				@fwrite($socket, $request);

				$response = '';
				while(!@feof($socket))
					$response .= @fgets($socket, 4096);

				fclose($socket);

				@list($headers, $body) = explode("\r\n\r\n", trim($response));

				$body = trim($body);

				return $body;
			}
		}

		/**
		 * Perform HTTP-request via stream
		 *
		 * @param $url
		 * @param string $method GET or POST
		 * @param array $params
		 * @return string
		 */
		function _performStreamRequest($url, $method = 'GET', $params = null)
		{
			// init socket timeout (default for PHP < 5.2.1)
			$iniSocketDefaultTimeout = ini_get('default_socket_timeout');
			ini_set('default_socket_timeout', ceil($this->timeout));

			$contextConfig = array(
				'http' => array(
					'method' => 'GET',
					'user_agent' => $this->getSurlyApiUseragent(),
					'timeout' => $this->timeout //PHP 5.2.1+
				)
			);

			if ($method == 'POST') {
				$contextConfig['http']['method'] = 'POST';

				if ($params) {
					$postData = $this->_buildQuery($params);
					$contextConfig['http']['header'] = join("\r\n",
								array(
									"Content-type: application/x-www-form-urlencoded",
									"Content-Length: " . strlen($postData),
								)
					) . "\r\n";
					$contextConfig['http']['content'] = $postData;
				}
			}

			$streamContext = stream_context_create($contextConfig);

			$result = null;
			if (intval(phpversion()) >= 5) {
				$result = @file_get_contents('http://' . $url, false, $streamContext);
			}
			else {
				$fp = @fopen('http://' . $url, 'r', false, $streamContext);

				if ($fp) {
					while(!@feof($fp))
						$result .= @fgets($fp, 4096);

					@fclose($fp);
				}
			}

			// restore defaults
			ini_set('default_socket_timeout', $iniSocketDefaultTimeout);

			return trim($result);
		}

		/**
		 * Chooses the right request handler for shortener and invokes it
		 *
		 * @param array $urls
		 * @return array
		 */
		function _callShortenerService($urls)
		{
			$shortenerApiUrl = $this->apiHost . $this->apiPath;
			$params = array(
				'raw' => 1,
				'urls' => implode("\r\n", $urls)
			);

			if (empty($urls)) {
				return array('urls' => array(), 'errors' => array());
			}

			$json = $this->_performRequest($shortenerApiUrl, 'POST', $params);

			// unbox response
			if ($json) {
				if (function_exists('json_decode')) {
					$result = @json_decode($json, true);
				}
				else {
					if (!class_exists('Services_JSON')) {
						require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'JSON.php');
					}

					$jsonService = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
					$result = @$jsonService->decode($json);
				}

				if (!$result || !is_array($result)) {
					$result = array('urls' => array(), 'errors' => array('Unrecognized result'));
				}
			}
			else {
				$result = array('urls' => array(), 'errors' => array('Connection error'));
			}

			return $result;
		}

		function _performRequest($url, $method = 'GET', $params = null)
		{
			if (function_exists('curl_init')) {
				$result = $this->_performCurlRequest($url, $method, $params);
			} else if (function_exists('stream_context_create')) {
				$result = $this->_performStreamRequest($url, $method, $params);
			} else {
				$result = $this->_performSocketRequest($url, $method, $params);
			}

			return $result;
		}

		/**
		 * Socket request handler.
		 *
		 * @param array $urls
		 * @return string
		 */
		function _callShortenerServiceSocket($urls)
		{
			$postData = 'raw=1&urls=' . urlencode(implode("\r\n", $urls));

			$request = join("\r\n", array(
				"POST " . $this->apiPath." HTTP/1.0",
				"Host: " . $this->apiHost,
				"Content-type: application/x-www-form-urlencoded",
				"Content-length: " . strlen($postData),
				"User-agent: " . $this->getSurlyApiUseragent(),
				"Connection: close",
				"",
				$postData
			));

			$socket = @fsockopen($this->apiHost, 80, $errno, $errstr, $this->timeout);
			if ($socket) {
				stream_set_timeout($socket, 0, $this->timeout * 1000000 /*microseconds*/);
				@fwrite($socket, $request);

				$response = '';
				while(!@feof($socket))
					$response .= @fgets($socket, 4096);

				fclose($socket);

				@list($headers, $body) = explode("\r\n\r\n", trim($response));

				$body = trim($body);

				return $body;
			}
		}

		function getSurlyApiUseragent() {
			return 'surly_api_caller (running '.(@$_SERVER['HTTP_HOST']).')';
		}

	}
}
