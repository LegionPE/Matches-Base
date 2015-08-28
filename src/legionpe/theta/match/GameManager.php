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

use legionpe\theta\query\NextIdQuery;
use pocketmine\scheduler\PluginTask;

class GameManager extends PluginTask{
	private $main;
	private $vacantMatches = [];
	private $runningMatches = [];
	public function __construct(MatchPlugin $main){
		parent::__construct($this->main = $main);
	}
	public function onRun($currentTick){
		if(count($this->vacantMatches) < 2){
			new InitiateMatchQuery($this->main, NextIdQuery::GAME);
		}
	}
	public function startNewMatch($gameId){
		$this->vacantMatches[$gameId] = $match = $this->main->getMatchImpl($gameId);
	}
	public function getRandomVacantMatch(){
		return $this->vacantMatches[array_keys($this->vacantMatches)[mt_rand(0, count($this->vacantMatches) - 1)]];
	}
}
