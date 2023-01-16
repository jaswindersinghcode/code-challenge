<?php

namespace CodeChallenge\Domain\Model\Slot;

class Slot
{
    private string $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $doctorId;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $end;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

    public function __construct(int $doctorId, \DateTime $start, \DateTime $end)
    {
        $this->doctorId = $doctorId;
        $this->start = $start;
        $this->end = $end;
        $this->createdAt = new \DateTime();
    }

    public function getStart(): \DateTime
    {
        return $this->start;
    }

    public function getDoctorId(): Int
    {
        return $this->doctorId;
    }

    public function setEnd(\DateTime $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function isStale(): bool
    {
        return $this->createdAt < new \DateTime('5 minutes ago');
    }
    public static function collectSlotsByDoctor($slots, $id){

        return array_filter($slots, function ($item) use ($id) {
            if ($item['doctor_id']==$id) {
                return true;
            }
            return false;
        });
    }
}