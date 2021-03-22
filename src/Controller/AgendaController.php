<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     * @Route("/agenda/show/{slug}", name="agenda_show")
     * @ParamConverter("agenda", options={"mapping": {"slug": "slug"}})
     */
    public function show(Agenda $agenda):Response
    {

        return $this->render('agenda/show.html.twig', [
            'agenda' => $agenda,
        ]);
    }

    /**
     * IsGranted("ROLE_EDITEUR")
     * @Route("/agenda/createNewAgenda", name="agenda_create")
     */
    public function create(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        /** @var User $user */
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('warning', 'Vous n\'etes pas autoriser');
            return $this->redirectToRoute('admin_index');
        }

        $agenda = new Agenda();
        
        $form = $this->createForm(AgendaType::class, $agenda);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
        
            if ($form->isValid()) {

                

                $image = $form->get('image')->getData();
                if ($image != null) {
                    //on genere un nouveau nom de fichier (codé) et on rajoute son extension
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    // on copie le fichier dans le dossier uploads
                    // 2 params (destination, fichier)
                    $image->move($this->getParameter('image_directory'), $fichier);

                    // on stock l'image dans la bdd (son nom)
                    $agenda->setImage($fichier);
                }

                /*date*/
                //format un peut special a cause de widget => single_text dans agendaType
                $date = $request->request->get('agenda')['date'];
                $date = explode('T', $date);

                $agenda->setDescription($form->get('description')->getData())
                    ->setTitre($form->get('titre')->getData())
                    ->setSlug(strtolower($slugger->slug($agenda->getTitre())))
                    ->setUser($user)
                    ->setDate(new \DateTime($date[0] . ' ' . $date[1]))
                    ->setCreatedAt(new \DateTime('now'));


                $em->persist($agenda);
                $em->flush();

                $this->addFlash('success', 'Evenement créé');
                return $this->redirectToRoute('admin_index');
            }

            $this->addFlash('warning', 'saisie invalide');
        }

        return $this->render('agenda/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * IsGranted("ROLE_EDITEUR")
     * @Route("/agenda/edit/{id}", name="agenda_edit")
     */
    public function edit(Agenda $agenda, Request $request, EntityManagerInterface $em, SluggerInterface $slugger):Response
    {

        /** @var User $user */
        $user = $this->getUser();

        /*on verifie que l'utilisateur connecté est peut modifier un événement
        * si l'evenement lui appartient ou si il est l'administrateur
        */
        if (!$this->isGranted('edit', $agenda)) {
            $this->addFlash('danger', 'action non autorisé');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(AgendaType::class,$agenda);
        $form->handleRequest($request);

        if($form->isSubmitted()){

            if($form->isValid()){

                $image = $form->get('image')->getData();
                if ($image != null) {
                    //on genere un nouveau nom de fichier (codé) et on rajoute son extension
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                    // on copie le fichier dans le dossier uploads
                    // 2 params (destination, fichier)
                    $image->move($this->getParameter('image_directory'), $fichier);

                    /* Penser a supprimer les ancien fichiers  */
                    if (!empty($agenda->getImage())) {
                        //le paramètre 'image_directory' est dans services.yaml
                        unlink($this->getParameter('image_directory') . '/' . $agenda->getImage()); //ici je supprime le fichier
                    }

                    // on stock l'image dans la bdd (son nom)
                    $agenda->setImage($fichier);
                }

                /*date*/
                //format un peut special a cause de widget => single_text dans agendaType
                $date = $request->request->get('agenda')['date'];
                $date = explode('T', $date);

                $agenda->setDescription($form->get('description')->getData())
                    ->setTitre($form->get('titre')->getData())
                    ->setSlug(strtolower($slugger->slug($agenda->getTitre())))
                    ->setDate(new \DateTime($date[0] . ' ' . $date[1]));
                    
                $em->persist($agenda);
                $em->flush();

                $this->addFlash('success', 'Evenement créé');
                return $this->redirectToRoute('admin_index');
            }

            $this->addFlash('warning','saisie invalide');
        }

        return $this->render('agenda/edit.html.twig', [
            'agenda' => $agenda,
            'form' => $form->createView()
        ]);
        
    }

    /**
     * IsGranted("ROLE_EDITEUR")
     * @Route("/agenda/delete/{id}", name="agenda_delete")
     */
    public function delete(Agenda $agenda, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {

        if(!$agenda){
            $this->addFlash('danger','Evenement introuvable');
            return $this->redirectToRoute('admin_index');
        }

        /** @var User $user */
        $user = $this->getUser();

        /*on verifie que l'utilisateur connecté peut supprimer un événement
        * si l'evenement lui appartient ou si il est l'administrateur
        */
        if (!$this->isGranted('delete', $agenda)) {
            $this->addFlash('danger', 'action non autorisé');
            return $this->redirectToRoute('admin_index');
        }

        //si on est toujours la, c'est qu'on peut supprimer l'event
        $em->remove($agenda);
        $em->flush();

        $this->addFlash('success','Agenda supprimer, toutes mes félicitation');
        return $this->redirectToRoute('admin_index');
    }
}
