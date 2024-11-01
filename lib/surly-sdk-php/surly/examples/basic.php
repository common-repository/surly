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

// import Sur.ly SDK library
require_once 'path_to_surly-sdk/Surly.php';

// make an instance
$surly = new Surly("your_settings_id");

// add domains to whitelist
// to avoid processing of links to them (optional)
$surly->whitelist(@$_SERVER['HTTP_HOST']);
$surly->whitelist("google.com");
$surly->whitelist("wikipedia.org");

// finally — process an HTML
$clearedHtml = $surly->process($yourHtml);
// process an url
$clearedHtml = $surly->processUrl($yourUrl);
// process urls
$clearedHtml = $surly->processMultipleUrls(array($yourUrl1, $yourUrl2));