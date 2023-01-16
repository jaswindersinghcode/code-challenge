<?php


namespace CodeChallenge\Infrastructure\Http\Controller;


use Symfony\Component\HttpFoundation\Response;
#[Route(path: "/check", name: "check_")]
class SiteController
{
    #[Route(path: "", name: "check", methods: ["GET"])]
    public function status()
    {
        return new Response('Service working properly');
    }
}
