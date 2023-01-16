<?php

namespace CodeChallenge\Infrastructure\Http\Controller;

use CodeChallenge\Application\Service\Doctor\DoctorSlotsSynchronizer;
use CodeChallenge\Application\Service\Doctor\StaticDoctorSlotsSynchronizer;
use CodeChallenge\Domain\Model\Slot\Slot;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DoctorSlotsSynchronizerController extends BaseController
{
    private $staticDoctorSlotsSynchronizer;

    private $doctorSlotsSynchronizer;
    public function __construct(
        StaticDoctorSlotsSynchronizer $staticDoctorSlotsSynchronizer,
        DoctorSlotsSynchronizer $doctorSlotsSynchronizer
    ) {
        $this->staticDoctorSlotsSynchronizer = $staticDoctorSlotsSynchronizer;
        $this->doctorSlotsSynchronizer = $doctorSlotsSynchronizer;
    }

    public function doctors(Request $request)
    {
        return $this->callService(
            function () use ($request) {
                return  json_decode($this->staticDoctorSlotsSynchronizer->getDoctors());
            });
    }

    public function slots(Request $request, $id)
    {
        return new JsonResponse(Slot::collectSlotsByDoctor(json_decode($this->staticDoctorSlotsSynchronizer->getSlots(), true), $id));
    }

    protected function getJsonStringAsArrayCollection(Request $request): ArrayCollection
    {
        $content = $request->getContent();

        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        return new ArrayCollection(json_decode($content, true));
    }
}
