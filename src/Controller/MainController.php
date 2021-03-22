<?php

namespace App\Controller;

use App\Repository\AgendaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(AgendaRepository $agendaRepository): Response
    {

        //$agendas = $agendaRepository->findAll();
        $agendas = $agendaRepository->findFuturAgenda('ASC');

        return $this->render('main/index.html.twig', [
            'agendas' => $agendas
        ]);
    }
}
