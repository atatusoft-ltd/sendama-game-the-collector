<?php

namespace SendamaEngine\TheCollector\Events;

use Sendama\Engine\Events\Enumerations\EventType;
use Sendama\Engine\Events\Event;

/**
 * NoMoreLivesEvent event class.
 * 
 * @package SendamaEngine\TheCollector\Events
 */
readonly class NoMoreLivesEvent extends Event
{
  public function __construct()
  {
    parent::__construct(EventType::GAME_PLAY);
  }
}