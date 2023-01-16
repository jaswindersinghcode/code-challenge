<?php

namespace CodeChallenge\Domain\Model\Slot;

interface SlotRepository
{
    public function findByCriteria(Criteria $criteria): ?Slot;
    public function save(Slot $slot): void;
    public function allSlotsByDoctor($id): ?array;
}
