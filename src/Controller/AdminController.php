<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AgendaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_EDITEUR')")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(AgendaRepository $agendaRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if($this->isGranted('ROLE_ADMIN')){
            $agendas = $agendaRepository->findAll();
        }else{
            $agendas = $agendaRepository->findBy(['user'=>$user]);
        }

        return $this->render('admin/index.html.twig', [
            'agendas' => $agendas,
        ]);
    }
}
