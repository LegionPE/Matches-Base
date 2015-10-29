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
use legionpe\theta\match\match\Match;
use legionpe\theta\query\AsyncQuery;

class MatchInfoQuery extends AsyncQuery{
	/** @var int */
	private $class;
	/** @var int */
	private $type;
	/** @var string */
	private $metadata;
	/** @var string[] */
	private $tags;
	/**
	 * @param Match $match
	 * @param LogInfo $info
	 * @param string[] $tags
	 */
	public function __construct(Match $match, LogInfo $info, array $tags = []){
		$this->mid = $match->getInstanceId();
		$this->type = $info->getType();
		$this->tags = "," . implode(",", array_map(function ($tag){
				return str_replace(",", "\\,\\", $tag);
			}, $tags)) . ",";
		$this->metadata = json_encode($info);
		parent::__construct($match->getMain());
	}
	public function getResultType(){
		return self::TYPE_RAW;
	}
	public function getQuery(){
		return "INSERT INTO logs.match_events (mid, class, type, time, tags, json) VALUES ($this->mid, $this->class, $this->type, unix_timestamp(), {$this->esc($this->tags)}, {$this->esc($this->metadata)})";
	}
}
