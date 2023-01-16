<?php

namespace CodeChallenge\Application\Service\Slot;

class SlotSynchronizerRequest
{
    private $slot;
    public function __construct(array $slot
    ){
        $this->slot = $slot;
    }

    public function slots()
    {
        return $this->slot;
    }

}