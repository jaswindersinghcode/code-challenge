<?php

namespace CodeChallenge\Tests\Infrastructure\Http\Controller;

use CodeChallenge\Application\Service\Doctor\DoctorSlotsSynchronizer;
use CodeChallenge\Application\Service\Doctor\DoctorSynchronizerRequest;
use CodeChallenge\Application\Service\Slot\SlotSynchronizerRequest;
use CodeChallenge\Infrastructure\Domain\Model\Doctor\InMemoryDoctorRepository;
use CodeChallenge\Infrastructure\Domain\Model\Slot\InMemorySlotRepository;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class DoctorSlotsSynchronizerControllerTest extends TestBase
{
    private $repository;
    private $slotsRepository;
    private $doctorslotSynchronizer;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryDoctorRepository();
        $this->slotsRepository = new InMemorySlotRepository();
        $this->doctorslotSynchronizer = new DoctorSlotsSynchronizer($this->repository, $this->slotsRepository, new Logger('logger', [new StreamHandler("php://stderr")]));
    }
    public function testSyncDoctorSlotAreCreated()
    {
        $doctorRequest =[["id"=>0,"name"=> "Adoring Shtern"], ["id"=> 1,"name"=> "Brave Ramanujan"]];
        $slotRequest =[
            ["id"=> 0, "doctor_id"=> 1,"start"=> "2023-01-15 11:00:00", "end"=> "2023-01-15 13:00:00"],
            ["id"=> 1, "doctor_id"=> 2,"start"=> "2023-01-15 15:00:00", "end"=> "2023-01-15 17:00:00"],
            ["id"=> 2, "doctor_id"=> 1, "start"=> "2023-01-15 16:00:00","end"=> "2023-01-15 17:00:00" ] ];

        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequest));

        $doctor = $this->repository->all();
        $this->assertCount(2, $doctor);

        $slots = $this->slotsRepository->all();
        $this->assertCount(2, $slots);

        $slotsSpecificDoctor = $this->slotsRepository->allSlotsByDoctor(1);
        $this->assertCount(2, (array) $slotsSpecificDoctor);

        $slotsSpecificDoctor = $this->slotsRepository->allSlotsByDoctor(2);
        $this->assertCount(0, (array) $slotsSpecificDoctor);
    }

    public function testIfDoctorGotError(){
        $doctorRequest =[["id"=>0,"name"=> "Adoring Shtern"], ["id"=> 1,"name"=> "Brave Ramanujan"], ["id"=> 2,"name"=> "Brave Jitu"]];
        $slotRequest =[
            ["id"=> 0, "doctor_id"=> 5,"start"=> "2023-01-15 11:00:00", "end"=> "2023-01-15 13:00:00"],
            ["id"=> 1, "doctor_id"=> 1,"start"=> "20w-01-15 15:00:00", "end"=> "2023-01-15 17:00:00"],
            ["id"=> 2, "doctor_id"=> 5, "start"=> "2023-01-15 16:00:00","end"=> "2023-01-15 17:00:00" ] ];

        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequest));

        $doctorWithError = $this->repository->findById(1);
        $this->assertTrue($doctorWithError->hasError());

        $doctorClear = $this->repository->findById(0);
        $this->assertFalse($doctorClear->hasError());

        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequest));
        $doctorUniques = $this->repository->all();
        $this->assertCount(3, $doctorUniques);
    }

    public function testIfDuplicateDoctor(){
        $doctorRequest =[["id"=>0,"name"=> "Adoring Shtern"], ["id"=> 2,"name"=> "Brave Ramanujan"]];
        $slotRequest =[];
        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequest));

        $doctor = $this->repository->all();
        $this->assertCount(2, $doctor);

        $doctorRequestAdd =[["id"=>0,"name"=> "Adoring Shtern"], ["id"=> 3,"name"=> "Brave Ramanujan"]];
        $slotRequestAdd =[];
        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequestAdd), new SlotSynchronizerRequest($slotRequestAdd));
        $doctor = $this->repository->all();
        $this->assertCount(3, $doctor);
    }

    public function testIfDuplicateSlots(){
        $doctorRequest =[["id"=>0,"name"=> "Adoring Shtern"]];
        $slotRequest =[
            ["id"=> 0, "doctor_id"=> 1,"start"=> "2023-01-15 11:00:00", "end"=> "2023-01-15 13:00:00"],
            ["id"=> 1, "doctor_id"=> 2,"start"=> "2023-01-15 15:00:00", "end"=> "2023-01-15 17:00:00"],
            ["id"=> 2, "doctor_id"=> 1, "start"=> "2023-01-15 16:00:00","end"=> "2023-01-15 17:00:00" ]];
        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequest));

        $slot = $this->slotsRepository->all();
        $this->assertCount(0, $slot);

        $slotRequestAdd =[
            ["id"=> 0, "doctor_id"=> 0,"start"=> "2023-01-15 11:00:00", "end"=> "2023-01-15 13:00:00"],
            ["id"=> 1, "doctor_id"=> 0,"start"=> "2023-01-15 15:00:00", "end"=> "2023-01-15 17:00:00"],
            ["id"=> 2, "doctor_id"=> 0,"start"=> "2023-01-15 16:00:00", "end"=> "2023-01-15 18:00:00" ]];
        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequestAdd));
        $slot = $this->slotsRepository->all();
        $this->assertCount(3, $slot);

        $slotRequestAdd2 =[
            ["id"=> 4, "doctor_id"=> 0,"start"=> "2023-01-15 12:00:00", "end"=> "2023-01-15 13:00:00"],
            ["id"=> 5, "doctor_id"=> 0,"start"=> "2023-01-15 14:00:00", "end"=> "2023-01-15 18:00:00"],
            ["id"=> 6, "doctor_id"=> 1,"start"=> "2023-01-15 15:00:00", "end"=> "2023-01-15 10:00:00" ]];
        $this->doctorslotSynchronizer->synchronizeDoctorSlots(new DoctorSynchronizerRequest($doctorRequest), new SlotSynchronizerRequest($slotRequestAdd2));
        $slot = $this->slotsRepository->all();
        $this->assertCount(3, $slot);
    }
}