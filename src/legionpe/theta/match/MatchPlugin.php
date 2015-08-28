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

use legionpe\theta\BasePlugin;
use pocketmine\Player;

abstract class MatchPlugin extends BasePlugin{
	private $gameManager;
	public function onEnable(){
		parent::onEnable();
		$this->getServer()->getScheduler()->scheduleRepeatingTask($this->gameManager = new GameManager($this), 10);
	}
	/**
	 * @return GameManager
	 */
	public function getGameManager(){
		return $this->gameManager;
	}
	public function getMatchImpl($gameId){
		return new BaseMatch($this, $gameId);
	}
	protected function createSession(Player $player, array $loginData){
		return new MatchSession($this, $player, $loginData);
	}
}
