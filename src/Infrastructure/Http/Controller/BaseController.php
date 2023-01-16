<?php


namespace CodeChallenge\Infrastructure\Http\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BaseController extends AbstractController
{
    protected function getInput(ArrayCollection $data, $key)
    {
        return (string)$data->containsKey($key) ? $data->get($key) : null;
    }

    protected function getJsonStringAsArrayCollection(Request $request): ArrayCollection
    {
        $content = $request->getContent();

        if (empty($content)) {
            throw new BadRequestHttpException();
        }
        return new ArrayCollection(json_decode($content, true));
    }

    protected function response($data, $status = 200)
    {
        return new JsonResponse(
            $data,
            $status,
            [
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET,POST,PATCH,PUT,OPTIONS,DELETE',
                'Content-type' => 'application/json',
                'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization'
            ]
        );
    }
    protected function responseRequest($status)
    {
        return new JsonResponse(
            null,
            $status
        );
    }
    protected function callService(callable $exec)
    {
        try {
            $result = $exec();
            $response = [
                'status' => 'ok'
            ];
            if (isset($result)) {
                $response['data'] = $result;
            };
            return $this->response($response);
        } catch (\Exception $exception) {
            return $this->response([
                'status' => 'error',
                'message' => $exception->getMessage(),
                'class' => get_class($exception)
            ]);
        }
    }

    public function options()
    {
        return $this->response(['status' => 'ok']);
    }
    public function getJsonDecode(string|bool $json): mixed
    {
        return json_decode(
            json: false === $json ? '' : $json,
            associative: true,
            depth: 16,
            flags: JSON_THROW_ON_ERROR,
        );
    }
}