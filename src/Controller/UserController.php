<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_index")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/user/pass", name="user_pass")
     */
    public function editPassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em):Response
    {   
        /** @var User $user */
        $user = $this->getUser();

        if(!$user){
            $this->addFlash('warning', 'action non autorisé');
            return $this->redirectToRoute('app_home');
        }

        //soumission du formulaire
        if($request->isMethod('POST')){

            $password = $request->request->get('password');
            $password2 = $request->request->get('password2');

            if($password == $password2){
                $user->setPassword($encoder->encodePassword($user,$password));

                $em->persist($user);
                $em->flush();

                $this->addFlash('success','Mot de passe changer');
                return $this->redirectToRoute('admin_index');
            }

            $this->addFlash('warning', 'le mot de passe n\'est pas valide');

        }

        return $this->render('user/pass.html.twig');
    }
}
