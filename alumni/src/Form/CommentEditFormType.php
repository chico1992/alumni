<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder 
            ->add('content',
                TextareaType::class,
                ['label' => 'Change the content of this flagged post']
            )
        ;
    if ($options['standalone']) {
        $builder->add(
            'submit', 
            SubmitType::class,
            ['attr'=>['class'=> 'btn btn-lg btn-dark btn-block']]
        );
    }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
            'standalone' => false,
        ]);
    }
}