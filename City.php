<?php

class City {

    public $pop;
    public $city;
    public $province;
    public $country;

    
    public function __construct($country="", $province="",$city="", $pop="") {

        $this->pop = $pop;
        $this->city = $city;
        $this->province = $province;
        $this->country = $country;
    }
  
    
    
}
