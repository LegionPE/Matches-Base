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

namespace legionpe\theta\match\log\joinquit;

use legionpe\theta\match\log\LogInfo;

class PlayerJoinLogInfo extends LogInfo{
	public $uid;

	public function getType(){
		return self::TYPE_JOINQUIT_JOIN_PLAYER;
	}
	public static function get($uid){
		$info = new PlayerJoinLogInfo;
		$info->uid = $uid;
		return $info;
	}
}
