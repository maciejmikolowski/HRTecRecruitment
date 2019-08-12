<?php
  namespace MaciejMikolowskiRekrutacjaHRtec\models\Date;

  interface MonthTranslate {
    const monthArray = [
      "01" => "stycznia",
      "02" => "lutego",
      "03" => "marca",
      "04" => "kwietnia",
      "05" => "maja",
      "06" => "czerwca",
      "07" => "lipca",
      "08" => "sierpnia",
      "09" => "września",
      "10" => "października",
      "11" => "listopada",
      "12" => "grudnia"
    ];
  }


  class Date {
    private $year;
    private $month;
    private $day;
    private $hour;
    private $minute;
    private $second;

    public function __construct($val) {
      $value = date('d m Y H m s',strtotime($val));
      $value = explode(" ", $value);
      $this->day = $value[0];
      $this->month = $value[1];
      $this->year = $value[2];
      $this->hour = $value[3];
      $this->minute = $value[4];
      $this->second = $value[5];

      $this->translateMonth();
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    private function translateMonth() {
      $this->month = MonthTranslate::monthArray[$this->month];
    }

    public function printDate() {
      return $this->day . " "
        . $this->month . " "
        . $this->year . " "
        . $this->hour . ":"
        . $this->minute . ":"
        . $this->second;
    }
  }
?>
