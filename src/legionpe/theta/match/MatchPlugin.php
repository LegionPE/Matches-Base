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
use legionpe\theta\config\Settings;
use legionpe\theta\match\log\joinquit\PlayerJoinLogInfo;
use legionpe\theta\match\log\joinquit\SpectatorJoinLogInfo;
use legionpe\theta\match\log\system\PreRunLogInfo;
use legionpe\theta\match\log\system\StartOpenLogInfo;
use legionpe\theta\match\match\Match;
use legionpe\theta\match\match\MatchConfiguration;
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
	public function addMatch($instanceId, MatchConfiguration $config){
		$this->matches[$instanceId] = $this->newMatch($instanceId, $config);
	}
	protected function newMatch($instanceId, $config){
		return new Match($this, $instanceId, $config);
	}
	public function removeMatch(Match $match){
		if(!$match->isClosed()){
			throw new \RuntimeException("Attempt to remove an unclosed match");
		}
		unset($this->matches[$match->getInstanceId()]);
	}
	/**
	 * @return MatchConfiguration
	 */
	public abstract function getNextConfiguration();

	// log types
	public function StartOpenLogInfo(/** @noinspection PhpUnusedParameterInspection */
		Match $match){
		return StartOpenLogInfo::get(Settings::$LOCALIZE_IP, Settings::$LOCALIZE_PORT, Settings::$SYSTEM_IS_TEST);
	}
	public function PreRunLogInfo(Match $match){
		return PreRunLogInfo::get(count($match->getPlayers()));
	}
	public function PlayerJoinLogInfo(Match $match, MatchSession $session){
		return PlayerJoinLogInfo::get($session->getUid(), count($match->getPlayers()));
	}
	public function SpectatorJoinLogInfo(/** @noinspection PhpUnusedParameterInspection */
		Match $match, MatchSession $session){
		return SpectatorJoinLogInfo::get($session->getUid());
	}
}
