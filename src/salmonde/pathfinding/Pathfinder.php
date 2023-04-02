<?php

declare(strict_types=1);

namespace salmonde\pathfinding;

use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use salmonde\pathfinding\astar\AStar;
use salmonde\pathfinding\utils\validator\DistanceValidator;
use salmonde\pathfinding\utils\validator\InsideWorldValidator;
use salmonde\pathfinding\utils\validator\JumpHeightValidator;
use salmonde\pathfinding\utils\validator\PassableValidator;
use function microtime;

class Pathfinder{

	private Algorithm $algorithm;
	private int $iterations = 0;
	private float $startTime = 0.0;

	public function __construct(World $world, Vector3 $startPos, Vector3 $targetPos, ?AxisAlignedBB $boundingBox = null, private float $timeout = 1.0, private int $maxIterations = 100000){
		$this->algorithm = new AStar($world, $startPos, $targetPos);

		$this->addDefaultValidators($boundingBox);
	}

	protected function addDefaultValidators(?AxisAlignedBB $boundingBox = null) : self{
		$highestPriority = $this->getAlgorithm()->getHighestValidatorPriority();
		$this->algorithm->addValidator(new InsideWorldValidator($highestPriority === 0 ? 100 : $highestPriority + 1))
			->addValidator(new PassableValidator($this->getAlgorithm()->getLowestValidatorPriority() - 1, $boundingBox ?? AxisAlignedBB::one()));
		return $this;
	}

	public function getAlgorithm() : Algorithm{
		return $this->algorithm;
	}

	public function findPath() : void{
		$this->startTime = microtime(true);
		$algorithm = $this->getAlgorithm();

		while(!$algorithm->isFinished() && $this->checkTime() && $this->checkIterations()){
			$algorithm->tick();
		}
	}

	protected function checkTime() : bool{
		return $this->timeout === 0.0 || microtime(true) - $this->startTime < $this->timeout;
	}

	protected function checkIterations() : bool{
		return $this->maxIterations === 0 || $this->iterations < $this->maxIterations;
	}

	public function getPathResult() : ?PathResult{
		return $this->getAlgorithm()->getPathResult();
	}

	public function setMaxDistance(int $maxDistance) : self{
		$this->algorithm->addValidator(new DistanceValidator($this->getAlgorithm()->getLowestValidatorPriority() - 1, $maxDistance));
		return $this;
	}

	public function setMaxJumpHeight(int $maxJumpHeight) : self{
		$this->algorithm->addValidator(new JumpHeightValidator($this->getAlgorithm()->getLowestValidatorPriority() - 1, $maxJumpHeight));
		return $this;
	}
}
