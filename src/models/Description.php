<?php
  namespace MaciejMikolowskiRekrutacjaHRtec\models\Description;

  class Description {
    private $value;
    private $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

    public function __construct($string) {
      $this->value = $string;
      $this->clearText();
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    private function clearText() {
      $this->value = strip_tags($this->value);

      $this->value = preg_replace($this->reg_exUrl, "", $this->value);
    }

    public function getDescription() {
      return $this->value;
    }
  }
?>
