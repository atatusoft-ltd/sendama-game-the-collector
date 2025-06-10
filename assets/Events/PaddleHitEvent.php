<?php

namespace SendamaEngine\TheCollector\Events;

use Sendama\Engine\Events\Enumerations\EventType;
use Sendama\Engine\Events\Event;

readonly class PaddleHitEvent extends Event
{
  public function __construct(protected int $points = 1)
  {
    parent::__construct(EventType::GAME_PLAY);
  }

  public function getPoints(): int
  {
    return $this->points;
  }
}