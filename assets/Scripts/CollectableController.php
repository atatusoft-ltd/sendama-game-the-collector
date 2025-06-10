<?php

namespace SendamaEngine\TheCollector\Scripts;

use Assegai\Collections\ItemList;
use RuntimeException;
use Sendama\Engine\Core\Behaviours\Behaviour;
use Sendama\Engine\Core\GameObject;
use Sendama\Engine\Core\Time;
use Sendama\Engine\Core\Vector2;
use Sendama\Engine\Debug\Debug;
use Sendama\Engine\Events\Interfaces\EventInterface;
use Sendama\Engine\Events\Interfaces\ObservableInterface;
use Sendama\Engine\Events\Interfaces\ObserverInterface;
use Sendama\Engine\Events\Interfaces\StaticObserverInterface;
use SendamaEngine\TheCollector\Events\OutOfBoundsEvent;
use SendamaEngine\TheCollector\Events\PaddleHitEvent;

class CollectableController extends Behaviour implements ObservableInterface
{
  /**
   * @var ItemList<ObserverInterface> $observers
   */
  protected ItemList $observers;
  /**
   * @var bool $canFall
   */
  protected bool $canFall = false;
  /**
   * @var float $fallSpeed
   */
  protected float $fallSpeed = 0.5;
  /**
   * @var float $nextFallTime
   */
  protected float $nextFallTime = 0;
  /**
   * @var int $pointValue
   */
  protected int $pointValue = 1;
  /**
   * @var GameObject $paddle
   */
  protected GameObject $paddle;

  /**
   * @inheritDoc
   */
  public function awake(): void
  {
    $this->observers = new ItemList(ObserverInterface::class);
  }

  /**
   * @inheritDoc
   */
  public function onStart(): void
  {
    // onStart is useful for initializing variables
    $this->resetPosition();
    $this->paddle = GameObject::findWithTag('paddle') ?? throw new RuntimeException('Paddle not found');
  }

  /**
   * @inheritDoc
   */
  public function onUpdate(): void
  {
    // onUpdate is called once per frame
    if ($this->canFall) {
      $this->fall();
    }
  }

  public function setPointValue(int $value = 1): void
  {
    $this->pointValue = clamp($value, 1, 9999);
  }

  public function startFalling(): void
  {
    if (!$this->isEnabled()) {
      $this->enable();
    }

    $this->resetPosition();
    $this->canFall = true;
  }

  public function stopFalling(): void
  {
    $this->canFall = false;
    $this->resetPosition();
    $this->getRenderer()->erase();
    $this->disable();
  }

  /**
   * Resets the position of the collectable
   *
   * @return void
   */
  public function resetPosition(): void
  {
    $y = 4;
    $x = rand(2, 79);

    $this->getTransform()->setPosition(new Vector2($x, $y));
  }

  /**
   * Makes the collectable fall
   *
   * @return void
   */
  public function fall(): void
  {
    if (Time::getTime() > $this->nextFallTime) {
      $this->getTransform()->translate(Vector2::up());

      if ($this->isOutOfBounds()) {
        $this->stopFalling();
        $this->notify(new OutOfBoundsEvent($this->getGameObject()));
      }

      if ($this->didHitPaddle()) {
        $this->stopFalling();
        $this->notify(new PaddleHitEvent($this->pointValue));
      }

      $this->nextFallTime = Time::getTime() + $this->fallSpeed;
    }
  }

  /**
   * @inheritDoc
   */
  public function addObservers(string|StaticObserverInterface|ObserverInterface ...$observers): void
  {
    foreach ($observers as $observer) {
      $this->observers->add($observer);
    }
  }

  /**
   * @inheritDoc
   */
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

  private function isOutOfBounds(): bool
  {
    if ($this->getTransform()->getPosition()->getY() > 24) {
      $this->getRenderer()->disable();
      return true;
    }

    return false;
  }

  /**
   * Checks if the collectable hit the paddle
   *
   * @return bool Whether the collectable hit the paddle or not
   */
  private function didHitPaddle(): bool
  {
    $position = $this->getTransform()->getPosition();
    $paddlePosition = $this->paddle->getTransform()->getPosition();

    if ($position->getY() === $paddlePosition->getY()) {
      if ($position->getX() >= $paddlePosition->getX() && $position->getX() <= $paddlePosition->getX() + 6) {
        $this->getRenderer()->disable();
        return true;
      }
    }

    return false;
  }

  public function reset(): void
  {
    $this->getRenderer()->disable();
    $this->stopFalling();
    $this->nextFallTime = 0;
  }
}
