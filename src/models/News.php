<?php
  namespace MaciejMikolowskiRekrutacjaHRtec\models\News;

  class News {
    private $title;
    private $description;
    private $link;
    private $pubDate;
    private $creator;

    public function __construct($title = null, $description = null, $link = null, $pubDate = null, $creator = null) {
      $this->title = $title;
      $this->description = $description;
      $this->link = $link;
      $this->pubDate = $pubDate;
      $this->creator = $creator;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function fillProps($itemProps) {
      foreach ($itemProps as $key => $value) {
        $this->$key = $value;
      }
    }
  }
?>
