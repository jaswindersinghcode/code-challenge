<?php

namespace CodeChallenge\Domain\Model\Doctor;

interface DoctorRepository
{
    public function findById($id): ?Doctor;

    public function save(Doctor $doctor): void;

    public function all(): array;
}
