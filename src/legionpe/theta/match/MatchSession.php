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

use legionpe\theta\match\match\Match;
use legionpe\theta\Session;
use pocketmine\Player;

abstract class MatchSession extends Session{
	/** @var MatchPlugin */
	private $main;
	/** @var Match|null */
	private $match = null;
	private $isPlayer = false;
	public function __construct(MatchPlugin $main, Player $player, array $loginData){
		parent::__construct($player, $loginData);
		$this->main = $main;
	}
	public function getMain(){
		return $this->main;
	}
	public function login($method){
		parent::login($method);
	}
	/**
	 * @return Match|null
	 */
	public function getMatch(){
		return $this->match;
	}
	/**
	 * @param Match|null $match
	 * @param bool $isPlayer
	 */
	public function setMatch($match, $isPlayer = false){
		$this->match = $match;
		$this->isPlayer = $isPlayer;
	}
	public function isPlayer(){
		return $this->isPlayer;
	}
	public function isSpectator(){
		return !$this->isPlayer;
	}
}
