<?php
declare(strict_types = 1);

namespace salmonde\pathfinding;

use pocketmine\block\Block;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use Ramsey\Collection\Set;
use salmonde\pathfinding\utils\validator\Validator;

abstract class Algorithm {

	private $world;
	protected $startPos;
	protected $targetPos;

	private $pathResult = null;

	private $validators;

	public function __construct(World $world, Vector3 $startPos, Vector3 $targetPos){
		$this->world = $world;
		$this->startPos = $startPos;
		$this->targetPos = $targetPos;
		$this->reset();

		$this->validators = new Set(Validator::class);
	}

	public function getWorld(): World{
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
		$this->validators->add($validator);
		$this->sortValidators();
	}

	public function removeValidator(Validator $validator): void{
		$index = $this->getValidators()->find($validator);

		if($index !== false){
			$this->getValidators()->remove($index);
			$this->sortValidators();
		}
	}

	protected function sortValidators(): void{
		$this->validators->sort('getPriority');
	}

	public function getValidators(): Set{
		return $this->validators;
	}

	protected function getValidatorPriorities(): array{
		$priorities = [];
		foreach($this->getValidators() as $validator){
			$priorities[] = $validator->getPriority();
		}

		return $priorities;
	}

	public function getHighestValidatorPriority(): int{
		if(count($this->getValidators()) === 0){
			return 0;
		}

		return max($this->getValidatorPriorities());
	}

	public function getLowestValidatorPriority(): int{
		if(count($this->getValidators()) === 0){
			return 0;
		}

		return min($this->getValidatorPriorities());
	}

	protected function isValidBlock(Block $block, int $side): bool{
		$oppositeSide = Facing::opposite($side);
		foreach($this->validators as $validator){
			if(!$validator->isValidBlock($this, $block, $oppositeSide)){
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
