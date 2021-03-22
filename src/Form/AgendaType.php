<?php

namespace App\Form;

use App\Entity\Agenda;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AgendaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $now = (new \DateTime('now'))->format('Y-m-d');

        $builder
            ->add('titre',TextType::class,[
                'label' => 'Titre de l\'événement',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Titre obligatoire'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre titre doit faire au moins {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('description', CKEditorType::class,[
                'label' => 'Description de l\'evenement',
                'constraints' => [
                    new NotBlank([
                        'message' => 'description obligatoire'
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => 'Votre description doit faire au moins {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ]
            ])
            ->add('image',FileType::class,[
                'label' => 'logo',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Quand ?',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => [
                    'placeholder' => 'Date de retour',
                    'min' => $now . 'T00:00'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agenda::class,
        ]);
    }
}
