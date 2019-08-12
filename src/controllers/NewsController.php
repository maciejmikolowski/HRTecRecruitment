<?php
  namespace MaciejMikolowskiRekrutacjaHRtec\controllers\NewsController;

  require_once(__DIR__."/../models/News.php");
  require_once(__DIR__."/../views/NewsView.php");
  require_once(__DIR__."/../models/Date.php");
  require_once(__DIR__."/../models/Description.php");
  require_once(__DIR__."/../models/Operations.php");

  use MaciejMikolowskiRekrutacjaHRtec\models\News as Model;
  use MaciejMikolowskiRekrutacjaHRtec\views\NewsView as View;
  use MaciejMikolowskiRekrutacjaHRtec\models\Date as Date;
  use MaciejMikolowskiRekrutacjaHRtec\models\Description as Description;
  use MaciejMikolowskiRekrutacjaHRtec\models\Operations as Operations;

  class NewsController {
    private $newsArray = array();
    private $output;
    private $rssInput;
    private $override;
    private $itemsFromFile;

    public function __construct($params) {
      $this->newsArray = array();
      $this->output = new View\NewsView($params->path, $params->operationType);
      $this->override = $params->operationType;
      $this->itemsFromFile = 0;

      try {
        @$this->rss = simplexml_load_file($params->url);
      }
      catch(Exception $e) {
        echo "ERROR: cannot read rss file from url.".PHP_EOL;
      }
    }

    public function getItemsFromFile() {
      if($this->override == Operations\Operations::extended) {
        $input = $this->output->getOldData();
        $header = array();
        if(!feof($input)) {
          $line = rtrim(fgets($input));
          $header = explode(";", $line);
        }

        while(!feof($input)) {
          $line = rtrim(fgets($input));
          if($line != "") {
            $this->addItemFromFile($line, $header);
            $this->itemsFromFile++;
          }
        }
      }
    }

    public function getItemsFromRSS() {
      if($this->rss !== null && is_object($this->rss)
        && $this->rss->channel !== null && is_object($this->rss->channel)
        && $this->rss->channel->item !== null) {
        foreach($this->rss->channel->item as $item) {
          $this->addItem($item);
        }

        return true;
      }
      else {
        echo "ERROR: invalid rss file, cannot read items.".PHP_EOL;
        return false;
      }
    }

    private function addItem($item) {
      $title = $item->title;
      $link = $item->link;
      $description = new Description\Description($item->description);
      $pubDate = new Date\Date($item->pubDate);
      $creator = $item->creator;

      array_push($this->newsArray, new Model\News(
        $title,
        $description->getDescription(),
        $link,
        $pubDate->printDate(),
        $creator
      ));
    }

    private function addItemFromFile($line, $order) {
      $line = explode('";"', $line);
      $lineElements = count($line);
      $line[0] = substr($line[0], 1);
      $line[$lineElements - 1] = substr($line[$lineElements - 1], 0, -1);
      $itemProps = array(
        'title' => '',
        'description' => '',
        'link' => '',
        'pubDate' => '',
        'creator' => '');

      $i = 0;
      foreach($line as $actProp) {
        try {
          $itemProps[$order[$i]] = $actProp;
        } catch(Exception $e) {
          echo "ERROR: invalid header in basic csv file";
        }
        $i++;
      }

      $newNews = new Model\News();
      $newNews->fillProps($itemProps);
      array_push($this->newsArray, $newNews);
    }

    public function printItems() {
      $counter = 0;
      $this->output->moveToBegin();

      $header = "title;description;link;pubDate;creator".PHP_EOL;
      $this->output->writeToFile($header);

      foreach($this->newsArray as $item) {
        $str = '"' .
         $item->title . '";"' .
         $item->description . '";"' .
         $item->link . '";"' .
         $item->pubDate . '";"' .
         $item->creator . '"'.PHP_EOL;
        $this->output->writeToFile($str);
        $counter++;
      }

      $retString = "CORRECT: " . $counter . " items saved";
      if($this->override == Operations\Operations::extended)
        $retString .= " (including " .$this->itemsFromFile. " items from basic file)";
      echo  $retString . "." . PHP_EOL;
    }
  }
?>
