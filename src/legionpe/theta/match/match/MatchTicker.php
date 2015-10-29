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

namespace legionpe\theta\match\match;

use legionpe\theta\match\MatchPlugin;
use pocketmine\scheduler\PluginTask;

class MatchTicker extends PluginTask{
	/** @var MatchPlugin */
	private $main;
	private $lastTick = null;
	public function __construct(MatchPlugin $main){
		parent::__construct($this->main = $main);
	}
	public function onRun($currentTick){
		$cnt = $this->lastTick === null ? 1 : time() - $this->lastTick;
		for($i = 0; $i < $cnt; $i++){
			$this->run();
		}
		$this->lastTick = time();
	}
	private function run(){
		foreach($this->main->getMatches() as $match){
			/** @noinspection PhpInternalEntityUsedInspection */
			$match->tick();
		}
	}
}
