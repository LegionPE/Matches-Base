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

namespace legionpe\theta\match\match;

use legionpe\theta\config\Settings;
use legionpe\theta\lang\Phrases;
use legionpe\theta\match\log\joinquit\PlayerJoinLogInfo;
use legionpe\theta\match\log\joinquit\SpectatorJoinLogInfo;
use legionpe\theta\match\log\system\StartOpenLogInfo;
use legionpe\theta\match\MatchPlugin;
use legionpe\theta\match\MatchSession;
use pocketmine\level\Level;
use RuntimeException;
use ZipArchive;

class Match{
	const STATE_OPEN = 0;
	const STATE_PRE_RUNNING = 1;
	const STATE_RUNNING = 2;
	const STATE_CLOSING = 3;

	/** @var MatchPlugin */
	private $main;
	/** @var int */
	private $instanceId;
	/** @var MatchConfiguration */
	private $config;
	/** @var int */
	private $state = self::STATE_OPEN;
	/** @var MatchSession[] */
	private $sessions = [];

	/** @var int internal counter in seconds */
	private $ticks = 0;
	/** @var string */
	private $worldName;
	/** @var Level */
	private $level;

	public function __construct(MatchPlugin $main, $instanceId, MatchConfiguration $config){
		$this->main = $main;
		$this->instanceId = $instanceId;
		$this->config = $config;
		$this->getMain()->getLogger()->notice("Instance ID: $this->instanceId");
		StartOpenLogInfo::get(Settings::$LOCALIZE_IP, Settings::$LOCALIZE_PORT, Settings::$SYSTEM_IS_TEST)->log($this);
	}
	/**
	 * @return MatchPlugin
	 */
	public function getMain(){
		return $this->main;
	}
	/**
	 * @return int
	 */
	public function getInstanceId(){
		return $this->instanceId;
	}
	/**
	 * @return int
	 */
	public function getState(){
		return $this->state;
	}
	/**
	 * @return bool
	 */
	public function isOpen(){
		return $this->state === self::STATE_OPEN;
	}
	/**
	 * @return bool
	 */
	public function isClosed(){
		return $this->state === self::STATE_CLOSING;
	}
	/**
	 * @return MatchSession[]
	 */
	public function getPlayers(){
		$out = [];
		foreach($this->sessions as $session){
			if($session->isPlayer()){
				$out[] = $session;
			}
		}
		return $out;
	}
	/**
	 * @return MatchSession[]
	 */
	public function getSpectators(){
		$out = [];
		foreach($this->sessions as $session){
			if(!$session->isPlayer()){
				$out[] = $session;
			}
		}
		return $out;
	}

	/**
	 * @return MatchConfiguration
	 */
	public function getConfig(){
		return $this->config;
	}

	/**
	 * This is the API function.
	 * @param MatchSession $session
	 * @param bool $isPlayer
	 * @return bool
	 */
	public function join(MatchSession $session, $isPlayer){
		if($isPlayer and !$this->isOpen() or $this->isClosed()){
			return false;
		}
		$this->sessions[$session->getUid()] = true;
		$session->setMatch($this, $isPlayer);
		if($isPlayer){
			$cnt = count($this->getPlayers());
			PlayerJoinLogInfo::get($session->getUid(), $cnt)->log($this);
			if($cnt === $this->config->minStartPlayers){
				$this->ticks = $this->config->maxWaitTime;
			}elseif($cnt === $this->config->maxPlayers){
				$this->ticks = $this->config->minWaitTime;
			}elseif($cnt > $this->config->minStartPlayers){ // max players already checked. DO NOT REMOVE ELSEIF
				$this->ticks = max($this->config->minWaitTime, $this->ticks);
			}
		}else{
			SpectatorJoinLogInfo::get($session->getUid())->log($this);
		}
		return true;
	}
	public function broadcast($phrase, $vars = [], $includeSpectators = true){
		foreach($this->sessions as $session){
			if($includeSpectators or $session->isPlayer()){
				$session->send($phrase, $vars);
			}
		}
	}
	public function broadcastMaintainedPopup($phrase, $vars = [], $includeSpectators = true){
		foreach($this->sessions as $session){
			if($includeSpectators or $session->isPlayer()){
				$session->setMaintainedPopup($session->translate($phrase, $vars));
			}
		}
	}
	public function broadcastTip($phrase, $vars = [], $includeSpectators = true){
		foreach($this->sessions as $session){
			if($includeSpectators or $session->isPlayer()){
				$session->getPlayer()->sendTip($session->translate($phrase, $vars));
			}
		}
	}

	/**
	 * @internal
	 * Do not call this function from anywhere other than {@link MatchTicker::run()}
	 */
	public function tick(){
		if($this->state === self::STATE_OPEN){
			if(count($this->getPlayers()) >= $this->config->minStartPlayers){
				$this->ticks--;
				if($this->ticks === 0){
					$this->startPreRunState();
					return;
				}
				$this->broadcastMaintainedPopup(Phrases::MATCH_TIME_BEFORE_CLOSE, ["secs" => $this->ticks]);
			}
		}
	}
	private function startPreRunState(){
		$this->state = self::STATE_PRE_RUNNING;
		$this->onPreRun();
	}
	protected function onPreRun(){
		$this->initWorld();
	}
	protected function initWorld(){
		$path = $this->getWorldZip();
		if($path !== null){
			$zip = new ZipArchive;
			if($zip->open($path) !== true){
				throw new RuntimeException("Could not load zip world $path");
			}
			$this->worldName = "match_{$this->getInstanceId()}";
			$target = $this->getMain()->getServer()->getDataPath() . "worlds/$this->worldName";
			mkdir($target);
			if(!$zip->extractTo($target)){
				throw new RuntimeException("Could not import zip world $path");
			}
			$zip->close();
		}else{
			$this->worldName = $this->getWorldName();
		}
		$this->getMain()->getServer()->loadLevel($this->worldName);
		$this->level = $this->getMain()->getServer()->getLevelByName($this->worldName);
	}
	protected function getWorldZip(){
		return null;
	}
	protected function getWorldName(){
		return $this->getMain()->query_world(); // implement either getWorldZip() or getWorldName()!
	}
}
