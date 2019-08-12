<?php
  namespace MaciejMikolowskiRekrutacjaHRtec;

  require_once(__DIR__."/controllers/ParamsController.php");
  require_once(__DIR__."/controllers/NewsController.php");

  use MaciejMikolowskiRekrutacjaHRtec\controllers\ParamsController as ParamsController;
  use MaciejMikolowskiRekrutacjaHRtec\controllers\NewsController as NewsController;


  if(count($argv) == 4) {
    $paramsObject = new ParamsController\ParamsController($argv[1], $argv[2], $argv[3]);

    if($paramsObject->validateParameters()) {
      $controller = new NewsController\NewsController($paramsObject);

      if(!$controller->getItemsFromRSS())
        exit();
      $controller->getItemsFromFile();
      $controller->printItems();
    }
  } else {
    echo "ERROR: Operation requires exactly 4 parameters.".PHP_EOL;
  }
?>
