<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
class CompanyController extends AbstractController
{
    #[Route('/{id}/employees', name: 'company', methods: ['GET'])]
    public function company(
        Company $company
    ): Response
    {
        try {
            $data = array_map(
                function($a) {
                    /** @var Job $a */
                    return $a->getEmployee()->getFirstName().' '.$a->getEmployee()->getLastName();
                },
                $company->getJobs()->toArray()
            );

            return $this->json(['status' => 'success', 'employees' => array_unique($data)], 201);
        } catch (\Exception $e) {
            return $this->json(['status' => 'fail', 'error' => $e], 500);
        }

    }
}
