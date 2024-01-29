<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OneController extends AbstractController
{

    #[Route('/one/try/{id}', name: 'try', methods: ['GET'])]
    public function essai(string $id): Response
    {
        return $this->render('one/try.html.twig', ['yoyo' => $id]);
    }

    public function list(): Response
    {
        return $this->render('one/list.html.twig');
    }

    #[Route(path: '/one/special/{_locale}/search.{_format}', locale: 'en', format: 'html', requirements: ['_locale' => 'en|fr', '_format' => 'html|xml'])]
    public function special(Request $request, $_locale, string $_format): Response {
        return $this->render('one/special.'.$_format.'.twig', ['locale' => $_locale, 'format' => $_format, 'attributes' => $request->attributes->all(), 'route' => $request->attributes->get('_route'), 'route_params' => $request->attributes->get('_route_params')]);
    }
}
