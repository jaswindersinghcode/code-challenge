<?php

namespace CodeChallenge\Infrastructure\Domain\Model\Slot;

use CodeChallenge\Domain\Model\Slot\Criteria;
use CodeChallenge\Domain\Model\Slot\Slot;
use CodeChallenge\Domain\Model\Slot\SlotRepository;

class InMemorySlotRepository implements SlotRepository
{
    private $models = [];

    public function findByCriteria(Criteria $criteria): ?Slot
    {
        $found = array_filter($this->models, function(Slot $model) use ($criteria){
            $condition = false;

            if ($criteria->doctorId() !== null) {
                if($criteria->doctorId()==($model->getDoctorId()) ){
                    $condition = true;
                }
            }
            if ($criteria->start() !== null) {
                if($criteria->start()==($model->getStart())){
                    $condition = true;
                }
            }
            return $condition;
        });
        if (!$found) {
            return null;
        }
        return clone $found[0];
    }
    public function save(Slot $slot): void
    {
        $this->models[] = clone $slot;
        $this->models = array_map("unserialize", array_unique(array_map("serialize", $this->models)));
    }
    public function all(): array
    {
        return $this->models;
    }
    public function allSlotsByDoctor($id): array {
        return array_filter($this->models, function (Slot $current) use ($id) {
            return $current->getDoctorId()==$id;
        });
    }

}