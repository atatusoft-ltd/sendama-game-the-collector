<?php

namespace SendamaEngine\TheCollector\Events;

use DateTimeInterface;
use Sendama\Engine\Events\Enumerations\EventType;
use Sendama\Engine\Events\Event;

/**
 * Represents a score event
 *
 * @package SendamaEngine\TheCollector\Events
 */
readonly class ScoreChangeEvent extends Event
{
  /**
   * ScoreEvent constructor
   *
   * @param int $currentScore The current score
   * @param int $previousScore The previous score
   */
  public function __construct(
    protected int $currentScore,
    protected int $previousScore
  )
  {
    parent::__construct(EventType::GAME_PLAY);
  }

  /**
   * Returns the current score
   *
   * @return int The current score
   */
  public function getCurrentScore(): int
  {
    return $this->currentScore;
  }

  /**
   * Returns the previous score
   *
   * @return int The previous score
   */
  public function getPreviousScore(): int
  {
    return $this->previousScore;
  }
}