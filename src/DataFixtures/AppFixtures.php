<?php

namespace App\DataFixtures;

use App\Entity\Agenda;
use App\Entity\AgendaComment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

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
                    ->setTitre($faker->title())
                    ->setImage('default.png')
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
