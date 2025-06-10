<?php

namespace SendamaEngine\TheCollector\Events;

use Sendama\Engine\Events\Enumerations\EventType;
use Sendama\Engine\Events\Event;

/**
 * Represents a life count event
 *
 * @package SendamaEngine\TheCollector\Events
 */
readonly class LifeCountChangeEvent extends Event
{
  /**
   * LifeCountChangeEvent constructor
   *
   * @param int $currentLifeCount The current life count
   * @param int $previousLifeCount The previous life count
   */
  public function __construct(
    protected int $currentLifeCount,
    protected int $previousLifeCount
  )
  {
    parent::__construct(EventType::GAME_PLAY);
  }

  /**
   * Returns the current life count
   *
   * @return int The current life count
   */
  public function getCurrentLifeCount(): int
  {
    return $this->currentLifeCount;
  }

  /**
   * Returns the previous life count
   *
   * @return int The previous life count
   */
  public function getPreviousLifeCount(): int
  {
    return $this->previousLifeCount;
  }
}