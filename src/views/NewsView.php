<?php
  namespace MaciejMikolowskiRekrutacjaHRtec\views\NewsView;

  class NewsView {
    private $file;
    private $operationType;

    public function __construct($filePath, $operationType) {
      $this->operationType = $operationType;

      if($operationType == 1)
        $this->file = fopen($filePath, "w");
      else
        $this->file = fopen($filePath, "r+");
    }

    public function __destruct() {
      fclose($this->file);
    }

    public function writeToFile($string) {
      fwrite($this->file, $string);
    }

    public function getOldData() {
      return $this->file;
    }

    public function moveToBegin() {
      rewind($this->file);
    }
  }
?>
