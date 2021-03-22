<?php

namespace App\Controller;

use App\Entity\Agenda;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AgendaController extends AbstractController
{
    /**
     * @Route("/agenda", name="agenda")
     */
    public function index(): Response
    {
        return $this->render('agenda/index.html.twig', [
            'controller_name' => 'AgendaController',
        ]);
    }

    /**
     * @Route("/agenda/{slug}", name="agenda_show")
     * @ParamConverter("agenda", options={"mapping": {"slug": "slug"}})
     */
    public function show(Agenda $agenda):Response
    {

        return $this->render('agenda/show.html.twig', [
            'agenda' => $agenda,
        ]);
    }
}
