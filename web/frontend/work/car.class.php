<?php
interface Car
{
  public function run();
  public function stop();
}

interface Passenger
{
  public function getIn($num);
  public function getOut($num);
}

class Lexus implements Car, Passenger
{
  const CAPACITY = 5;
  private static $passenger = 0;
  private $isRun = false;
  
  public function run()
  {
    if (self::$passenger == 0) {
      throw new Exception("It doesn't move. There is no driver.");
    } else {
      $this->isRun = true;
    }
  }

  public function stop()
  {
    if (self::$passenger == 0) {
      throw new Exception("It doesn't stop. There is no driver.");
    } else {
      $this->isRun = false;
    }
  }

  public function getIn($num = 1)
  {
    $passenger = self::$passenger + $num;
    if ($this->isRun()) {
      throw new Exception("Dangerous! It's running now.");
    } elseif ($passenger > self::CAPACITY) {
      throw new Exception("Sorry. It's overloaded.");
    } else {
      self::$passenger = $passenger;
    }
  }

  public function getOut($num = 1)
  {
    $passenger = self::$passenger - $num;
    if ($this->isRun()) {
      throw new Exception("Dangerous! It's running now.");
    } elseif ($people < 0) {
      throw new Exception("It doesn't get on so much.");
    } else {
      self::$passenger = $passenger;
    }
  }
  
  public function getPassenger()
  {
    return self::$passenger;
  }

  public function isRun()
  {
    return $this->isRun;
  }
  
  public function showStatus()
  {
    echo 'The car is ' . ($this->isRun() ? 'running.' : 'stopped.');
    echo ' -> Passenger : ' . $this->getPassenger();
  }
}

try {
  $lexus = new Lexus();

  $lexus->getIn();
  $lexus->run();

  $lexus->stop();
  $lexus->getIn(3);

  $lexus->run();
  echo $lexus->showStatus();
} catch (Exception $e) {
  echo $e->getMessage();
}

