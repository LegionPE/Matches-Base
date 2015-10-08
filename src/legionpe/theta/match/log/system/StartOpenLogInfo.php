<?php

/*
 * LegionPE
 *
 * Copyright (C) 2015 PEMapModder and contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PEMapModder
 */

namespace legionpe\theta\match\log\system;

use legionpe\theta\match\log\LogInfo;

class StartOpenLogInfo extends LogInfo{
	/** @var string */
	public $ip;
	/** @var int */
	public $port;
	/** @var bool */
	public $isTest;
	public function getType(){
		return self::TYPE_SYSTEM_OPEN;
	}
	public static function get($ip, $port, $isTest = false){
		$info = new StartOpenLogInfo;
		$info->ip = $ip;
		$info->port = $port;
		$info->isTest = $isTest;
		return $info;
	}
}
