<?php

namespace SendamaEngine\TheCollector\Scripts;

use Sendama\Engine\Core\Behaviours\Behaviour;
use Sendama\Engine\Events\Interfaces\EventInterface;
use Sendama\Engine\Events\Interfaces\ObservableInterface;
use Sendama\Engine\Events\Interfaces\ObserverInterface;
use Sendama\Engine\Exceptions\Scenes\SceneNotFoundException;
use SendamaEngine\TheCollector\Events\NoMoreLivesEvent;

class LevelController extends Behaviour implements ObserverInterface
{
  protected CollectableSpawner $collectableSpawner;
  protected StatTracker $statTracker;

  public function onStart(): void
  {
    // onStart is useful for initializing variables
    $this->collectableSpawner = $this->getComponent(CollectableSpawner::class);
    $this->statTracker = $this->getComponent(StatTracker::class);
  }

  public function onUpdate(): void
  {
    // onUpdate is called once per frame
  }

  /**
   * @param ObservableInterface $observable
   * @param EventInterface $event
   */
  public function onNotify(ObservableInterface $observable, EventInterface $event): void
  {
    if ($event instanceof NoMoreLivesEvent) {
      if (confirm('Game Over! Play again?')) {
        $this->restartLevel();
      } else {
        quitGame();
      }
    }
  }

  protected function restartLevel(): void
  {
    $this->statTracker->reset();
    $this->collectableSpawner->reset();
    loadScene(1);
  }
}
