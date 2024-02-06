<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Employee;
use App\Entity\Job;
use App\Repository\CompanyRepository;
use App\Repository\EmployeeRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/employee')]
class EmployeeController extends AbstractController
{

    #[Route('/add', name: 'employee_add', methods: ['POST'])]
    public function addEmployee(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            $employee = new Employee();
            $employee->setFirstName($data['firstName']);
            $employee->setLastName($data['lastName']);
            $employee->setBirthDate(new \DateTime($data['birthDate']));
            $employee->setCreated(new \DateTime());
            $employee->setUpdated(new \DateTime());
            $errors = $validator->validate($employee);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;

                return new Response($errorsString, 422);
            }

            $entityManager->persist($employee);

            $entityManager->flush();

            return $this->json(['status' => 'success'], 201);
        } catch (\Exception $e) {
            return $this->json(['status' => 'fail', 'error' => $e], 500);
        }
    }

    #[Route('/add/job', name: 'employee_job_add', methods: ['POST'])]
    public function addEmployeeJob(
        EntityManagerInterface $entityManager,
        Request $request,
        ValidatorInterface $validator,
        CompanyRepository $companyRepository,
        EmployeeRepository $employeeRepository
    ): Response
    {
        try {
            $data = json_decode($request->getContent(), true);

            $job = new Job();
            $job->setName($data['name']);
            $job->setEmployee($employeeRepository->findOneBy(['id' => $data['employee_id']]));
            $job->setCompany($companyRepository->findOneBy(['id' => $data['company_id']]));
            $job->setStartDate(new \DateTime($data['start_date']));
            $job->setEndDate(!empty($data['end_date']) ? new \DateTime($data['end_date']) : null);
            $job->setCreated(new \DateTime());
            $job->setUpdated(new \DateTime());
            $errors = $validator->validate($job);

            if (count($errors) > 0) {
                $errorsString = (string)$errors;

                return new Response($errorsString, 422);
            }

            $entityManager->persist($job);

            $entityManager->flush();

            return $this->json(['status' => 'success'], 201);
        } catch (\Exception $e) {
            return $this->json(['status' => 'fail', 'error' => $e], 500);
        }
    }

    #[Route('/list', name: 'employee_list', methods: ['GET'])]
    public function list(
        EmployeeRepository $employeeRepository
    ): Response
    {
        try {
            $data = $employeeRepository->findBy([], ['lastName' => 'ASC']);

            return $this->json(
                [
                    'status' => 'success',
                    'employees' => array_map(
                        function($a) {
                        /** @var Employee $a */
                            return [
                                'first_name' => $a->getFirstName(),
                                'last_name' => $a->getLastName(),
                                'birth_date' => $a->getBirthDate()->format('Y-m-d H:i:s'),
                                'jobs' => array_map(
                                    function($b) {
                                        /** @var Job $b */
                                        return [
                                            'name' => $b->getName(),
                                            'start_date' => $b->getStartDate()->format('Y-m-d H:i:s'),
                                            'end_date' => $b->getStartDate() ? '' :  $b->getEndDate()->format('Y-m-d H:i:s'),
                                            'company' => $b->getCompany()->getName()
                                        ];
                                    },
                                    array_filter(
                                        $a->getJobs()->toArray(),
                                        function($c) {
                                            /** @var Job $c */
                                            $now  = new \DateTime();
                                            return $c->getStartDate() < $now && $c->getEndDate() > $now;
                                        }
                                    )
                                ),
                            ];
                        },
                        $data
                    )
                ]
            );
        } catch (\Exception $e) {
            return $this->json(['status' => 'fail', 'error' => $e], 500);
        }

    }

    #[Route('/{id}', name: 'employee', methods: ['GET'])]
    public function employee(
        Employee $employee,
        Request $request,
        EmployeeRepository $employeeRepository
    ): Response
    {
        try {
            $now = new \DateTime();
            $startDate = $request->get('start_date') ? new \DateTime($request->get('start_date')) : $now->modify('-1 month');
            $endDate = $request->get('end_date') ? new \DateTime($request->get('end_date')) : new \DateTime();
            $data = array_map(
                function($b) {
                    /** @var Job $b */
                    return [
                        'id' => $b->getId(),
                        'name' => $b->getName(),
                        'start_date' => $b->getStartDate()->format('Y-m-d H:i:s'),
                        'end_date' => $b->getEndDate()->format('Y-m-d H:i:s')

                    ];
                },
                array_filter(
                $employee->getJobs()->toArray(),
                function($a) use ($startDate, $endDate) {
                    /** @var Job $a */
                    return $a->getStartDate() < $endDate && $a->getEndDate() > $startDate;
                },
            ));

            return $this->json(['status' => 'success', 'jobs' => $data], 201);
        } catch (\Exception $e) {
            return $this->json(['status' => 'fail', 'error' => $e], 500);
        }

    }
}
