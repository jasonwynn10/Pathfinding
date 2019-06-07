<?php
declare(strict_types = 1);

namespace salmonde\pathfinding;

use Ds\Vector;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use salmonde\pathfinding\utils\validator\Validator;

abstract class Algorithm {

	private $world;
	protected $startPos;
	protected $targetPos;

	private $pathResult = null;

	private $validators;

	public function __construct(Level $world, Vector3 $startPos, Vector3 $targetPos){
		$this->world = $world;
		$this->startPos = $startPos;
		$this->targetPos = $targetPos;
		$this->reset();

		$this->validators = new Vector();
	}

	public function getWorld(): Level{
		return $this->world;
	}

	public function setStartPos(Vector3 $startPos): void{
		$this->startPos = $startPos;
		$this->reset();
	}

	public function getStartPos(): Vector3{
		return $this->startPos;
	}

	public function setTargetPos(Vector3 $targetPos): void{
		$this->targetPos = $targetPos;
		$this->reset();
	}

	public function getTargetPos(): Vector3{
		return $this->targetPos;
	}

	public function addValidator(Validator $validator): void{
		$this->validators->push($validator);
		$this->validators->sort(function(Validator $v1, Validator $v2): int{
			return $v2->getPriority() - $v1->getPriority();
		});
	}

	public function getValidators(): ValidatorSequence{
		return $this->validators;
	}

	protected function isValidBlock(Block $block): bool{
		foreach($this->validators as $validator){
			if(!$validator->isValidBlock($this, $block)){
				return false;
			}
		}

		return true;
	}

	protected function setPathResult(PathResult $pathResult): void{
		$this->pathResult = $pathResult;
	}

	public function getPathResult(): ?PathResult{
		return $this->pathResult;
	}

	public function resetPathResult(): void{
		$this->pathResult = null;
	}

	abstract public function reset(): void;

	abstract public function tick(): void;

	abstract public function isFinished(): bool;
}
