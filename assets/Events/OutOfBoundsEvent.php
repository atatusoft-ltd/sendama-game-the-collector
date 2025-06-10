<?php

namespace SendamaEngine\TheCollector\Events;

use Sendama\Engine\Core\GameObject;
use Sendama\Engine\Events\Enumerations\EventType;
use Sendama\Engine\Events\Event;

readonly class OutOfBoundsEvent extends Event
{
  public function __construct(protected GameObject $collectable)
  {
    parent::__construct(EventType::GAME_PLAY);
  }

  public function getCollectable(): GameObject
  {
    return $this->collectable;
  }
}