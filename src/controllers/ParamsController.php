<?php
  namespace MaciejMikolowskiRekrutacjaHRtec\controllers\ParamsController;

  require_once(__DIR__.'/../models/Operations.php');

  use  MaciejMikolowskiRekrutacjaHRtec\models\Operations as Operations;

  // controller for parameters passed by the console
  class ParamsController {
    private $operationType;
    private $url;
    private $path;

    public function __construct($operationType, $url, $path) {

      // type of operation
      if($operationType == "csv:simple")
        $this->operationType = Operations\Operations::simple;
      else if($operationType == "csv:extended")
        $this->operationType = Operations\Operations::extended;
      else
        $this->operationType = Operations\Operations::notSupported;

      // url to fetch data
      if (filter_var($url, FILTER_VALIDATE_URL))
        $this->url = $url;
      else
        $this->url = null;

      // path to output csv file
      $this->setPath($path);
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    private function setPath($path) {
      $path2 = explode('/', $path);
      array_pop($path2);
      $path2 = implode('/', $path2);
      // check the existing of directory
      if($this->folder_exist($path2)) {
        // check the extension and file existing
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if($ext === "csv") {
          if(file_exists($path) && $this->operationType === Operations\Operations::simple ) {
            $this->confirmPath($path);
          } else {
            $this->path = $path;
          }
        } else {
          $this->path = null;
        }
      } else {
        $this->path = null;
      }
    }

    private function confirmPath($path) {
      echo "WARNING: you may loose data from the existing file. Continue? (y/n)".PHP_EOL;
      $choice = fread(STDIN, 1);
      if($choice == "y") {
        $this->path = $path;
      } else {
        echo "OPERATION CANCELLED";
        exit();
      }
    }

    private function folder_exist($folder) {
      $path = realpath($folder);
      return ($path !== false && is_dir($path)) ? $path : false;
    }

    public function validateParameters() {
      if($this->operationType > 0 && $this->url !== null && $this->path !== null) {
        return true;
      } else {
        if($this->operationType === Operations\Operations::notSupported)
          echo "ERROR: Invalid operation type parameter.".PHP_EOL;

        if($this->url === null)
          echo "ERROR: Invalid url to fetch data.".PHP_EOL;

        if($this->path === null)
          echo "ERROR: Invalid path for csv file.".PHP_EOL;

        return false;
      }
    }
  }
?>
