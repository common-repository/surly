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

/**
 * Surly with local cache using Memcache
 */
class MemcacheSurly extends Surly
{
	private $memcache;
	private $rootStatusKey = 'surly_root_status';
	var $isRootDomainAlive = null;

	function __construct(Memcache $memcache, $toolbarId)
	{
		$this->memcache = $memcache;
		parent::Surly($toolbarId);
	}

	/**
	 * This method accepts an associative array containing a key-value pairs
	 * of target URLs and their corresponding IDs to be stored in cache
	 * (either memcached or any RDBMS).
	 * @param array $url2shortIds
	 * @return void
	 */
	function cacheShortIds($url2shortIds)
	{
		foreach ($url2shortIds as $url => $shortId) {
			$this->memcache->set(md5($url), $shortId);
		}
	}

	/**
	 * This method accepts a collection of target URLs
	 * (without "http://www" prefix)
	 * and returns an associative array containing a key-value pairs
	 * of target URLs and their corresponding IDs found in cache.
	 *
	 * @param array $urls
	 * @return array
	 */
	function getCachedShortIds($urls)
	{
		$url2shortIds = array();

		foreach ($urls as $url) {
			$shortId = $this->memcache->get(md5($url));
			if ($shortId) {
				$url2shortIds[$url] = $shortId;
			}
		}

		return $url2shortIds;
	}
	/**
	 * This method returns cached root status
	 * 
	 * @return array|bool|string
	 */
	function getCachedRootStatus()
	{
		return $this->memcache->get($this->rootStatusKey);
	}

	/**
	 * This method accepts a root status string
	 * 
	 * @param string $rootStatus
	 */
	function cacheRootStatus($rootStatus)
	{
		$this->memcache->set($this->rootStatusKey, $rootStatus);
	}
}