<?php

declare(strict_types=1);

namespace salmonde\pathfinding\utils\validator;

use pocketmine\block\Block;
use salmonde\pathfinding\Algorithm;

class DistanceValidator extends Validator{

	private int $maxDistanceSquared;

	public function __construct(int $priority, int $maxDistance){
		parent::__construct($priority);
		$this->maxDistanceSquared = $maxDistance ** 2;
	}

	public function isValidBlock(Algorithm $algorithm, Block $block, int $fromSide) : bool{
		return $algorithm->getStartPos()->distanceSquared($block->getPosition()) <= $this->maxDistanceSquared;
	}
}
