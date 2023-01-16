<?php
declare(strict_types=1);

namespace CodeChallenge\Application\Service\Doctor;

use CodeChallenge\Application\Service\Slot\SlotSynchronizerRequest;
use CodeChallenge\Domain\Model\Doctor\Doctor;
use CodeChallenge\Domain\Model\Doctor\DoctorRepository;
use CodeChallenge\Domain\Model\Slot\Criteria;
use CodeChallenge\Domain\Model\Slot\Slot;
use CodeChallenge\Domain\Model\Slot\SlotRepository;
use DateTime;
use JsonException;
use Psr\Log\LoggerInterface;

class DoctorSlotsSynchronizer
{
    protected LoggerInterface $logger;
    private DoctorRepository $doctorRepository;
    private SlotRepository $slotRepository;
    public function __construct(DoctorRepository $doctorRepository,
                                SlotRepository $slotRepository,
                                LoggerInterface $logger)
    {
        $this->doctorRepository = $doctorRepository;
        $this->slotRepository = $slotRepository;
        $this->logger = $logger;
    }

    public function synchronizeDoctorSlots(DoctorSynchronizerRequest $request, SlotSynchronizerRequest $slotRequest): void{

        $doctors = $request->getDoctors();

        foreach ($doctors as $doctor) {
            $name = $this->normalizeName($doctor['name']);
            $doctorEntity = $this->doctorRepository->findById($doctor['id']) ?? new Doctor((string)$doctor['id'], $name);
            $doctorEntity->setName($name);
            $doctorEntity->clearError();
            foreach ($this->fetchDoctorSlots($doctor['id'], $slotRequest) as $slot) {
                if (false === $slot) {
                    $doctorEntity->markError();
                } else {
                    $this->slotRepository->save($slot);
                }
            }
            $this->doctorRepository->save($doctorEntity);
        }
    }

    protected function fetchDoctorSlots(int $id, SlotSynchronizerRequest $slotRequest): iterable
    {
        try {
            $slots = Slot::collectSlotsByDoctor($slotRequest->slots(), $id);
            yield from $this->parseSlots($slots, $id);
        } catch (JsonException) {
            if ($this->shouldReportErrors()) {
                 $this->logger->info('Error fetching slots for doctor', ['doctorId' => $id]);
            }
            yield false;
        }
    }

    protected function normalizeName(string $fullName): string
    {
        [, $surname] = explode(' ', $fullName);

        /** @see https://www.youtube.com/watch?v=PUhU3qCf0Nk */
        if (0 === stripos($surname, "o'")) {
            return ucwords($fullName, ' \'');
        }

        return ucwords($fullName);
    }

    protected function parseSlots($slots, int $id) : iterable
    {
        try {
            foreach ($slots as $slot) {
                $start = new DateTime($slot['start']);
                $end = new DateTime($slot['end']);
                $criteria = new Criteria();
                $criteria->setDoctorId($id);
                $criteria->setStart($start);
                $slot = $this->slotRepository->findByCriteria($criteria) ?: new Slot($id, $start, $end);
                if (false === $slot->isStale()) {
                    yield $slot->setEnd($end);
                }
            }
        }catch (\Exception){
            yield false;
        }
    }

    protected function shouldReportErrors(): bool
    {
        return (new DateTime())->format('D') !== 'Sun';
    }
}
