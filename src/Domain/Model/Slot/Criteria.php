<?php

namespace CodeChallenge\Domain\Model\Slot;

class Criteria
{
    private $idDoctor;
    private $startDate;

    public function setDoctorId($id){
        $this->idDoctor = $id;
    }

    public function setStart($date){
        $this->startDate = $date;
    }

    public function doctorId(){
        return $this->idDoctor;
    }

    public function start(){
        return $this->startDate;
    }
}