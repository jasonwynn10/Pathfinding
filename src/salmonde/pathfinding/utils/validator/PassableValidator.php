<?php

declare(strict_types=1);

namespace salmonde\pathfinding\utils\validator;

use pocketmine\block\Block;
use pocketmine\math\AxisAlignedBB;
use salmonde\pathfinding\Algorithm;

class PassableValidator extends Validator{

	public function __construct(int $priority, private AxisAlignedBB $boundingBox){
		parent::__construct($priority);
	}

	public function isValidBlock(Algorithm $algorithm, Block $block, int $fromSide) : bool{
		if($block->isSolid()){
			return false;
		}

		$blockPos = $block->getPosition();
		$boundingBox = $this->boundingBox->offsetCopy($blockPos->x, $blockPos->y, $blockPos->z);
		foreach($algorithm->getWorld()->getCollisionBlocks($boundingBox) as $collidingBlock){
			if($collidingBlock->isSolid()){
				return false;
			}
		}

		return true;
	}
}
