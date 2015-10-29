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

namespace legionpe\theta\match\log;

use legionpe\theta\match\match\Match;
use legionpe\theta\match\query\MatchInfoQuery;

abstract class LogInfo{
	const TYPE_SYSTEM_OPEN = 0x0000;
	const TYPE_SYSTEM_PREPARE = 0x0001;
	const TYPE_SYSTEM_RUN = 0x0002;
	const TYPE_SYSTEM_CLOSE = 0x0003;

	const TYPE_JOINQUIT_JOIN_PLAYER = 0x0100;
	const TYPE_JOINQUIT_JOIN_SPECTATOR = 0x0101;

	public $_meta_class;
	public function __construct(){
		$this->_meta_class = get_class($this);
	}
	/**
	 * @return int
	 */
	public abstract function getType();
	/**
	 * @param Match $match
	 * @param string[] $tags
	 */
	public function log(Match $match, array $tags = []){
		new MatchInfoQuery($match, $this, $tags);
	}
}
