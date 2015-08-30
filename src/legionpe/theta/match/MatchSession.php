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

use legionpe\theta\Session;
use pocketmine\Player;

class MatchSession extends Session{
	/** @var MatchPlugin */
	private $main;
	/** @var BaseMatch|null */
	private $match = null;
	public function __construct(MatchPlugin $main, Player $player, array $loginData){
		parent::__construct($player, $loginData);
		$this->main = $main;
	}
	public function getMain(){
		return $this->main;
	}
	/**
	 * @return BaseMatch|null
	 */
	public function getCurrentMatch(){
		return $this->match;
	}
	public function login($method){
		parent::login($method);

	}
}
