<?php

namespace SendamaEngine\TheCollector\Scripts;

use Sendama\Engine\Core\Behaviours\Behaviour;
use Sendama\Engine\Core\GameObject;
use Sendama\Engine\Core\Time;

class CollectableSpawner extends Behaviour
{
  const int MAX_COLLECTABLES = 30;
  /**
   * @var GameObject[] $collectables
   */
  protected array $collectables = [];
  protected float $nextSpawnTime = 0;
  protected float $spawnInterval = 2;
  protected int $nextSpawnIndex = 0;

  public function onStart(): void
  {
    // onStart is useful for initializing variables
  }

  public function onUpdate(): void
  {
    // onUpdate is called once per frame
    if (Time::getTime() > $this->nextSpawnTime) {
      $this->spawnCollectable();
      $this->nextSpawnTime = Time::getTime() + $this->spawnInterval;
    }
  }

  private function spawnCollectable(): void
  {
    $collectable = $this->collectables[$this->nextSpawnIndex];
    if (!$collectable->isActive()) {
      $collectable->activate();
    }
    $collectable->getRenderer()->enable();

    $controller = $collectable->getComponent(CollectableController::class);
    assert($controller instanceof CollectableController);
    $controller->startFalling();

    $this->nextSpawnIndex = wrap($this->nextSpawnIndex + 1, 0, self::MAX_COLLECTABLES - 1);
  }

  public function setCollectables(array $collectables): void
  {
    $this->collectables = $collectables;
  }

  public function reset(): void
  {
    foreach ($this->collectables as $collectable) {
      if ($collectableController = $collectable->getComponent(CollectableController::class) ) {
        assert($collectableController instanceof CollectableController);
        $collectableController->reset();
      }
    }
  }
}
