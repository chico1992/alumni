<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\DTO\UserSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add(
            'search',
            TextType::class,
            ['required'=>false]
        );

        if($options['standalone']){
            $builder->add(
                'submit', 
                SubmitType::class, 
                [
                    'label' => 'Search',
                    'attr' => ['class' => 'btn btn-lg btn-dark btn-block']
                ]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'standalone' => false
        ]);
    }
}
