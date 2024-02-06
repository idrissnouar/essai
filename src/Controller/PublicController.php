<?php

namespace App\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        $client = new Client();
        $response = $client->request(
            'POST',
            'https://www.symf.vm/employee/add/job',
            [
                'verify' => false,
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'body' => '{"employee_id":1,"name":"Job 1","company_id":1,"start_date":"2024-01-01 00:00:00","end_date":"2024-01-31 00:00:00"}'
            ]
        );
        var_dump($response->getBody()->getContents());
        die();
    }
}
