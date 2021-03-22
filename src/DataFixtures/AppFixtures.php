<?php

namespace App\DataFixtures;

use App\Entity\Agenda;
use App\Entity\AgendaComment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    private $slugger;
    

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $admin = new User();
        $admin->setEmail('admin@gmail.com')
            ->setPassword(\password_hash('admin',PASSWORD_BCRYPT))
            ->setPseudo('Administrateur')
            ->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);

        for($i=0; $i<10; $i++)
        {
            $faker = Factory::create('fr_FR');

            $user = new User();
            $user->setEmail($faker->email)
                ->setPseudo($faker->firstName())
                ->setRoles(['ROLE_EDITEUR'])
                ->setPassword(\password_hash('password',PASSWORD_BCRYPT));
            
            $manager->persist($user);

            for($j=0; $j<10; $j++){
                $agenda = new Agenda();
                $agenda->setCreatedAt(new \DateTime('now'))
                    ->setDate($faker->dateTimeBetween('-1 year', '+2 year'))
                    ->setTitre($faker->text(20))
                    ->setImage('default.png')
                    ->setSlug(\strTolower($this->slugger->slug($agenda->getTitre())))
                    ->setDescription($faker->text(500))
                    ->setUser($user);

                $manager->persist($agenda);

                for($v=0; $v<2; $v++){
                    $comment = new AgendaComment();
                    $comment->setAgenda($agenda)
                        ->setCommentaire($faker->text(155))
                        ->setCreatedAt(new \DateTime('now'))
                        ->setUser($user);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
