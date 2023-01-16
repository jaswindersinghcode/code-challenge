<?php

namespace CodeChallenge\Infrastructure\Domain\Model\Doctor;

use CodeChallenge\Domain\Model\Doctor\Doctor;
use CodeChallenge\Domain\Model\Doctor\DoctorRepository;

class InMemoryDoctorRepository implements DoctorRepository
{
    private $models = [];

    public function findById($id): ?Doctor
    {
        $found = array_values(array_filter($this->models, function (Doctor $current) use ($id) {
            return ($current->getId()==($id));
        }));

        if (!$found) {
            return null;
        }

        return clone $found[0];
    }

    public function save(Doctor $doctor): void
    {
        $this->models[] = clone $doctor;
        $this->models = array_map("unserialize", array_unique(array_map("serialize", $this->models)));
    }

    public function all(): array
    {
        return $this->models;
    }
}