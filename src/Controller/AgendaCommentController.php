<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Agenda;
use App\Entity\AgendaComment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Agenda $agenda, Request $request, EntityManagerInterface $em):Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('warning','Vous devez être connecté pour poster un commentaire');
            return $this->redirectToRoute('agenda_show', ['slug' => $agenda->getSlug()]);
        }

        $comment = new AgendaComment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid())
            {
                $comment->setAgenda($agenda)
                    ->setUser($user)
                    ->setCommentaire($form->get('commentaire')->getData())
                    ->setCreatedAt(new \DateTime('now'));

                $em->persist($comment);
                $em->flush($comment);

                $this->addFlash('success', 'Commentaire enregistré');
                return $this->redirectToRoute('agenda_show',['slug'=> $agenda->getSlug()]);
            }

            $this->addFlash('warning','Mauvaise saisie');
        }

        return $this->render('agenda_comment/create.html.twig',[
            'agenda'=>$agenda,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/agenda/comment/edit/{id}", name="agenda_comment_edit")
     */
    public function edit(AgendaComment $comment, Request $request, EntityManagerInterface $em):Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // si pas utilisateur connecté on redirige
        if (!$user) {
            $this->addFlash('warning', 'Vous devez être connecté pour poster un commentaire');
            return $this->redirectToRoute('agenda_show', ['slug' => $comment->getAgenda()->getSlug()]);
        }

        //utilisation d'un voter pour verifier les droit de l'utilisateur connecté
        /*on verifie que l'utilisateur connecté est le 'proprietaire' du commentaire*/
        if (!$this->isGranted('edit', $comment)) {
            $this->addFlash('danger', 'action non autorisé');
            return $this->redirectToRoute('agenda_show', ['slug' => $comment->getAgenda()->getSlug()]);
        }

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $comment->setCommentaire($form->get('commentaire')->getData())
                        ->setCreatedAt(new \DateTime('now'));

                $em->persist($comment);
                $em->flush($comment);

                $this->addFlash('success', 'Commentaire enregistré');
                return $this->redirectToRoute('agenda_show', ['slug' => $comment->getAgenda()->getSlug()]);
            }

            $this->addFlash('warning', 'Mauvaise saisie');
        }

        return $this->render('agenda_comment/create.html.twig', [
            'agenda' => $comment->getAgenda(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/agenda/comment/delete/{id}", name="agenda_comment_delete")
     */
    public function delete(AgendaComment $comment, EntityManagerInterface $em)
    {
        //on memorise l'agenda du commentaire pour pouvoir rediriger après la suppression
        $agenda = $comment->getAgenda();

        if(!$comment){
            $this->addFlash('warning','commentaire introuvable');
            return $this->redirectToRoute('agenda_show', ['slug' => $comment->getAgenda()->getSlug()]);
        }

        //utilisation d'un voter pour verifier les droit de l'utilisateur connecté
        /*on verifie que l'utilisateur connecté est le 'proprietaire' du commentaire*/
        if (!$this->isGranted('delete', $comment)) {
            $this->addFlash('danger', 'action non autorisé');
            return $this->redirectToRoute('agenda_show', ['slug' => $comment->getAgenda()->getSlug()]);
        }

        $em->remove($comment);
        $em->flush();

        $this->addFlash('success','Commentaire supprimé');
        return $this->redirectToRoute('agenda_show',['slug'=>$agenda->getSlug()]);
    }
}
