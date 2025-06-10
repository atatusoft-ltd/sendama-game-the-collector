<?php

namespace SendamaEngine\TheCollector\Scenes;

use Sendama\Engine\Core\Behaviours\SimpleQuitListener;
use Sendama\Engine\Core\Scenes\AbstractScene;
use Sendama\Engine\Core\GameObject;
use Sendama\Engine\Core\Texture2D;
use Sendama\Engine\Core\Vector2;
use Sendama\Engine\UI\Label\Label;
use SendamaEngine\TheCollector\Scripts\CollectableController;
use SendamaEngine\TheCollector\Scripts\CollectableSpawner;
use SendamaEngine\TheCollector\Scripts\HudController;
use SendamaEngine\TheCollector\Scripts\LevelController;
use SendamaEngine\TheCollector\Scripts\PaddleController;
use SendamaEngine\TheCollector\Scripts\StatTracker;

class Level extends AbstractScene
{
  const int MAX_COLLECTABLES = 30;

  public function awake(): void
  {
    // awake is called when the scene is loaded
    $this->environmentTileMapPath = 'Maps/level';

    // create your game objects here
    $levelManager = new GameObject('Level Manager');
    $uiManager = new GameObject('UI Manager');
    $paddle = new GameObject('My Game Object', 'paddle', position: Vector2::one());
    $collectables = [];

    $levelManager->addComponent(SimpleQuitListener::class);
    $levelController = $levelManager->addComponent(LevelController::class);
    $statTracker = $levelManager->addComponent(StatTracker::class);
    $collectableSpawner = $levelManager->addComponent(CollectableSpawner::class);

    for($index = 0; $index < self::MAX_COLLECTABLES; $index++) {
      $collectableTexture = new Texture2D('Textures/collectable.texture');
      $count = $index + 1;
      $collectable = new GameObject("Collectable $count");
      $collectable->setSpriteFromTexture($collectableTexture, Vector2::zero(), Vector2::one());
      $collectable->getTransform()->setPosition(new Vector2(40, -2));
      $collectableController = $collectable->addComponent(CollectableController::class);
      $collectableController->addObservers($statTracker);

      $collectables[] = $collectable;
      $collectable->getRenderer()->disable();
    }

    $collectableSpawner->setCollectables($collectables);

    $hudController = $uiManager->addComponent(HudController::class);
    $statTracker->addObservers($levelController, $hudController);

    $scoreLabel = new Label($this, '', new Vector2(3, 2), new Vector2(10, 1));
    $livesLabel = new Label($this, '', new Vector2(3, 3), new Vector2(10, 1));
    $hudController->setScoreLabel($scoreLabel);
    $hudController->setLifeCountLabel($livesLabel);

    $paddleTexture = new Texture2D('Textures/paddle.texture');
    $paddle->setSpriteFromTexture($paddleTexture, Vector2::zero(), new Vector2(6, 1));
    $paddle->addComponent(PaddleController::class);

    // add the game objects to the scene
    $this->add($levelManager);
    $this->add($paddle);
    $this->add($uiManager);
    $this->add($scoreLabel);
    $this->add($livesLabel);

    foreach ($collectables as $collectable) {
      $this->add($collectable);
    }
  }
}
