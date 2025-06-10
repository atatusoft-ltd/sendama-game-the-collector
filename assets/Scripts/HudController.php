<?php

namespace SendamaEngine\TheCollector\Scripts;

use Sendama\Engine\Core\Behaviours\Behaviour;
use Sendama\Engine\Events\Interfaces\EventInterface;
use Sendama\Engine\Events\Interfaces\ObservableInterface;
use Sendama\Engine\Events\Interfaces\ObserverInterface;
use Sendama\Engine\UI\Label\Label;
use SendamaEngine\TheCollector\Events\LifeCountChangeEvent;
use SendamaEngine\TheCollector\Events\ScoreChangeEvent;

class HudController extends Behaviour implements ObserverInterface
{
  protected ?Label $scoreLabel = null;
  protected ?Label $lifeCountLabel = null;

  public function onStart(): void
  {
    // onStart is useful for initializing variables
    $this->setScore(0);
    $this->setLifeCount(3);
  }

  public function onUpdate(): void
  {
    // onUpdate is called once per frame
  }

  /**
   * @param Label $scoreLabel
   */
  public function setScoreLabel(Label $scoreLabel): void
  {
    $this->scoreLabel = $scoreLabel;
  }

  /**
   * @param Label $lifeCountLabel
   */
  public function setLifeCountLabel(Label $lifeCountLabel): void
  {
    $this->lifeCountLabel = $lifeCountLabel;
  }

  public function setScore(int $score, int $min = 0, int $max = 9999): void
  {
    $formattedScore = sprintf('%04d', $score);
    $this->scoreLabel?->setText("Score: $formattedScore");
  }

  public function setLifeCount(int $lives, int $min = 0, int $max = 3): void
  {
    $formattedLifeCount = sprintf('  x%d', clamp($lives, $min, $max));
    $this->lifeCountLabel?->setText("Lives: $formattedLifeCount");
  }

  public function onNotify(ObservableInterface $observable, EventInterface $event): void
  {
    if ($event instanceof ScoreChangeEvent) {
      $this->setScore($event->getCurrentScore());
    }

    if ($event instanceof LifeCountChangeEvent) {
      $this->setLifeCount($event->getCurrentLifeCount());
    }
  }
}
