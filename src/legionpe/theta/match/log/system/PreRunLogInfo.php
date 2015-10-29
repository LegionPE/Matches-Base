<?php

/*
 * LegionPE
 *
 * Copyright (C) 2015 PEMapModder
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

class PreRunLogInfo extends LogInfo{
	public $numPlayers;
	public static function get($numPlayers){
		$info = new PreRunLogInfo;
		$info->numPlayers = $numPlayers;
		return $info;
	}
	/**
	 * @return int
	 */
	public function getType(){
		return self::TYPE_SYSTEM_PREPARE;
	}
}
