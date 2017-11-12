<?php

/**
 * This is the model for a flight
 *
 * @author Brayden Traas
 */
class Flight extends Entity
{
    protected $id;
    protected $code;
    protected $airplane;
    protected $departAirport;
    protected $departTime;
    protected $arriveAirport;
    protected $arriveTime;

    /*
     * ['field' => 'id', 'label' => 'Plane', 'rules' => 'alpha_numeric_spaces|max_length[64]'],
            ['field' => 'code', 'label' => 'Manufacturer', 'rules' => 'alpha_numeric_spaces|max_length[64]'],
            ['field' => 'airplanes', 'label' => 'Model', 'rules' => 'integer'],
            ['field' => 'depart_airport', 'label' => 'Price', 'rules' => 'integer'],
            ['field' => 'depart_time', 'label' => 'Seats', 'rules' => 'alpha_numeric_spaces|max_length[64]'],
            ['field' => 'arrive_airport', 'label' => 'Reach', 'rules' => 'integer'],
            ['field' => 'arrive_time', 'label' => 'Cruise', 'rules' => 'alpha_numeric_spaces|max_length[64]']
     */


    /*
     * There are some restrictions on your schedule:
	• no departures before 08:00
	• no landings after 22:00
	• minimum 30 minutes between a plane's landing and any subsequent departure
	• all of your fleet must be back at your airline base by the end of the day
	• flight times need to be reasonable, based on distance between airports, airplane cruising speed,
            and a 10 minute buffer added to each flight in order to reach cruising/landing speed and altitude
     */

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $departure integer timestamp
     * @param $arrival integer timestamp
     * @param $airports array
     * @param $plane integer id
     * @return bool on valid
     */
    protected static function validateFlightTimes($departure, $arrival, $airports, $plane) {


    }



    public function setId($value) {


        // check valid character type
        $alNum = preg_replace('/[^a-z0-9 ]/i', '', $value);
        if($value != $alNum) return;

        if(strlen($value) > 64) {
            return;
        }

        $this->id = $value;
    }

    public function setCode($value){

        // check valid character type
        $alNum = preg_replace('/[^a-z0-9 ]/i', '', $value);
        if($value != $alNum) return;

        if(strlen($value) > 64) {
            return;
        }

        $this -> code = $value;
    }

    public function setAirplane($value){
        $alNum = preg_replace('/[^0-9]/i', '', $value);
        if($value != $alNum) return;

        if($value != intval($value)) return;


        if(isset($this->departTime) && isset($this->arriveTime)) {
            $valid = (new FlightSchedule)->validatePlaneAvailable($value, $this->departTime, $this->arriveTime);
            if(!$valid) return;
        }


        $this -> airplane = $value;
    }

    public function setDepartAirport($value){

        $alNum = preg_replace('/[^0-9]/i', '', $value);
        if($value != $alNum) return;

        if($value != intval($value)) return;

        $this -> departAirport = $value;
    }

    public function setDepartTime($value){

        // check valid character type
//        $alNum = preg_replace('/[^a-z0-9 ]/i', '', $value);
//        if($value != $alNum) return;
//
//        if(strlen($value) > 64) {
//            return;
//        }

        if($value != intval($value)) return;

        if(date("G",$value) < 8) return; // no departures before 8am


        if(isset($this->arriveTime) && isset($this->airplane)) {
            $valid = (new FlightSchedule)->validatePlaneAvailable($this->airplane, $value, $this->arriveTime);
            if(!$valid) return;
        }

        $this -> departTime = $value;
    }

    public function setArriveAirport($value){

        $alNum = preg_replace('/[^0-9]/i', '', $value);
        if($value != $alNum) return;

        if($value != intval($value)) return;

        $this -> arriveAirport = $value;
    }

    public function setArriveTime($value){

        if($value != intval($value)) return;

        if(date("G",$value) > 21) return; // no departures after 22:00

        if(isset($this->departTime) && isset($this->airplane)) {
            $valid = (new FlightSchedule)->validatePlaneAvailable($this->airplane, $this->departTime, $value);
            if(!$valid) return;
        }

        $this -> arriveTime = $value;
    }

}