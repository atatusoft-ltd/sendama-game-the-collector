<?php

namespace SendamaEngine\TheCollector\Scripts;

use Sendama\Engine\Core\Behaviours\Behaviour;
use Sendama\Engine\Core\Vector2;
use Sendama\Engine\IO\Enumerations\AxisName;
use Sendama\Engine\IO\Input;

class PaddleController extends Behaviour
{
  protected Vector2 $startPosition;
  protected int $moveSpeed = 2;

  public function onStart(): void
  {
    $this->setStartPosition(new Vector2(37, 23));
  }

  public function onUpdate(): void
  {
    $h = Input::getAxis(AxisName::HORIZONTAL);

    if (abs($h) > 0) {
      $this->move($h);
    }
  }

  /**
   * Move the paddle
   *
   * @param int $direction The direction to move the paddle
   * @return void
   */
  public function move(int $direction): void
  {
    $effectiveDirection = $direction * $this->moveSpeed;

    if ($this->getTransform()->getPosition()->getX() + $effectiveDirection < 2) {
      return;
    }

    if ($this->getTransform()->getPosition()->getX() + $effectiveDirection + 6 > 80) {
      return;
    }

    $this->getTransform()->translate(new Vector2($effectiveDirection, 0));
  }

  /**
   * Set the start position of the paddle
   *
   * @param Vector2 $position The start position of the paddle
   * @return void
   */
  public function setStartPosition(Vector2 $position): void
  {
    $this->startPosition = $position;
    $this->getTransform()->setPosition($this->startPosition);
  }
}
