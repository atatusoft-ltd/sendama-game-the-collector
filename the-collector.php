<?php

require __DIR__ . '/vendor/autoload.php';

use Amasiye\Figlet\FontName;
use Sendama\Engine\Game;
use Sendama\Engine\Core\Scenes\TitleScene;
use Sendama\Engine\UI\Menus\MenuItems\MenuItem;
use SendamaEngine\TheCollector\Scenes\Level;

function bootstrap(): void
{
  $gameName = "The Collector"; // This will be overwritten by the .env file if GAME_NAME is set
  $game = new Game($gameName, 80, 30);

  $titleScene = new TitleScene('Title Screen');
  $titleScene
    ->addMenuItems(new MenuItem('Settings'))
    ->setTitleFont(FontName::ANSI_SHADOW)
    ->setTitle($gameName);

  $game->addScenes(
    $titleScene,
    new Level('Level 01'),
  );

  $game
    ->loadSettings()
    ->run();
}

bootstrap();