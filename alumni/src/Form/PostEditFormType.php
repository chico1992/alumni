<?php

namespace App\Form;


use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\VisibilityGroup;

class PostEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                ['label' => 'Edit the title']
            )   
            ->add('content',
                TextareaType::class,
                ['label' => 'Edit the post']

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
            'data_class' => Post::class,
            'standalone' => false,
        ]);
    }
}

