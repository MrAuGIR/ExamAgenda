<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\AgendaComment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgendaCommentController extends AbstractController
{
    /**
     * @Route("/agenda/comment", name="agenda_comment")
     */
    public function index(): Response
    {
        return $this->render('agenda_comment/index.html.twig', [
            'controller_name' => 'AgendaCommentController',
        ]);
    }

    /**
     * @Route("/agenda/{id}/comment/create", name="agenda_comment_create")
     */
    public function create(Agenda $agenda)
    {

        return $this->render('agenda_comment/create.html.twig');
    }

    /**
     * @Route("/agenda/comment/edit/{id}", name="agenda_comment_edit")
     */
    public function edit(AgendaComment $agendaComment)
    {

    }

    /**
     * @Route("/agenda/comment/delete/{id}", name="agenda_comment_delete")
     */
    public function delete(AgendaComment $agendaComment)
    {

    }
}
