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

namespace legionpe\theta\match\query;

use legionpe\theta\match\log\LogInfo;
use legionpe\theta\match\MatchPlugin;
use legionpe\theta\query\AsyncQuery;

class MatchInfoQuery extends AsyncQuery{
	/** @var int */
	private $type;
	/** @var string */
	private $metadata;
	/** @var string[] */
	private $tags;
	/**
	 * @param MatchPlugin $main
	 * @param LogInfo $info
	 * @param string[] $tags
	 */
	public function __construct(MatchPlugin $main, LogInfo $info, array $tags = []){
		$this->mid = $main->getInstanceId();
		$this->type = $info->getType();
		$this->tags = "," . implode(",", array_map(function ($tag){
				return str_replace(",", "\\,\\", $tag);
			}, $tags)) . ",";
		$this->metadata = json_encode($info);
		parent::__construct($main);
	}
	public function getResultType(){
		return self::TYPE_RAW;
	}
	public function getQuery(){
		return "INSERT INTO match_events (mid, type, time, tags, json) VALUES ($this->mid, $this->type, unix_timestamp(), {$this->esc($this->tags)}, {$this->esc($this->metadata)})";
	}
}
