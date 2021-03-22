<?php

namespace App\Form;

use App\Entity\AgendaComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentaire', CKEditorType::class,[
                'label' => 'Votre commentaire',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Votre commentaire ne peut pas être vide'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AgendaComment::class,
        ]);
    }
}
