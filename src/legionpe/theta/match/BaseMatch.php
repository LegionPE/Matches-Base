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

namespace legionpe\theta\match;

class BaseMatch{
	/** @var MatchPlugin */
	private $main;
	/** @var int */
	private $gameId;
	public function __construct(MatchPlugin $main, $gameId){
		$this->main = $main;
		$this->gameId = $gameId;
	}
}
