<?php

namespace SendamaEngine\TheCollector\Scripts;

use Assegai\Collections\ItemList;
use Sendama\Engine\Core\Behaviours\Behaviour;
use Sendama\Engine\Events\Interfaces\EventInterface;
use Sendama\Engine\Events\Interfaces\ObservableInterface;
use Sendama\Engine\Events\Interfaces\ObserverInterface;
use Sendama\Engine\Events\Interfaces\StaticObserverInterface;
use SendamaEngine\TheCollector\Events\LifeCountChangeEvent;
use SendamaEngine\TheCollector\Events\NoMoreLivesEvent;
use SendamaEngine\TheCollector\Events\OutOfBoundsEvent;
use SendamaEngine\TheCollector\Events\PaddleHitEvent;
use SendamaEngine\TheCollector\Events\ScoreChangeEvent;

class StatTracker extends Behaviour implements ObserverInterface, ObservableInterface
{
  protected int $score = 0;
  protected int $lives = 3;

  protected ItemList $observers;

  public function awake(): void
  {
    $this->observers = new ItemList(ObserverInterface::class);
  }

  public function onStart(): void
  {
    // onStart is useful for initializing variables
  }

  public function onUpdate(): void
  {
    // onUpdate is called once per frame
  }

  public function reset(): void
  {
    $previousLives = $this->lives;
    $this->lives = 3;
    $this->notify(new LifeCountChangeEvent($this->lives, $previousLives));

    $previousScore = $this->score;
    $this->score = 0;
    $this->notify(new ScoreChangeEvent($this->score, $previousScore));
  }

  public function onNotify(ObservableInterface $observable, EventInterface $event): void
  {
    if ($event instanceof OutOfBoundsEvent) {
      $this->decrementPlayerLives();
    }

    if ($event instanceof PaddleHitEvent) {
      $this->incrementPlayerScore($event->getPoints());
    }
  }

  protected function incrementPlayerScore(int $points = 1): void
  {
    $previousScore = $this->score;
    $this->score = $previousScore + $points;
    $this->notify(new ScoreChangeEvent($this->score, $previousScore));
  }

  private function decrementPlayerLives(): void
  {
    $previousLives = $this->lives;
    $this->lives = clamp($this->lives - 1, 0, 3);
    $this->notify(new LifeCountChangeEvent($this->lives, $previousLives));

    if ($this->lives === 0) {
      $this->notify(new NoMoreLivesEvent());
    }
  }

  public function addObservers(string|StaticObserverInterface|ObserverInterface ...$observers): void
  {
    foreach ($observers as $observer) {
      $this->observers->add($observer);
    }
  }

  public function removeObservers(string|StaticObserverInterface|ObserverInterface|null ...$observers): void
  {
    foreach ($observers as $observer) {
      $this->observers->remove($observer);
    }
  }

  public function notify(EventInterface $event): void
  {
    /** @var ObserverInterface $observer */
    foreach ($this->observers as $observer) {
      $observer->onNotify($this, $event);
    }
  }
}
