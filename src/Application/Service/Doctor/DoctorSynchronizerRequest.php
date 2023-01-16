<?php

namespace CodeChallenge\Application\Service\Doctor;

class DoctorSynchronizerRequest
{
    private $doctors;
    public function __construct(array $doctors
    )
    {
        $this->doctors = $doctors;
    }

    public function getDoctors()
    {
        return $this->doctors;
    }

}