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
use legionpe\theta\match\match\Match;
use legionpe\theta\match\utils\FireMatchesTask;

abstract class MatchPlugin extends BasePlugin{
	/** @var Match[] */
	private $matches = [];
	public function onEnable(){
		parent::onEnable();
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new FireMatchesTask($this), 10);
	}
	/**
	 * @return Match[]
	 */
	public function getMatches(){
		return $this->matches;
	}
	public function addMatch($instanceId){
		$this->matches[$instanceId] = new Match($this, $instanceId);
	}
	public function removeMatch(Match $match){
		if(!$match->isClosed()){
			throw new \RuntimeException("Attempt to remove an unclosed match");
		}
		unset($this->matches[$match->getInstanceId()]);
	}
}
