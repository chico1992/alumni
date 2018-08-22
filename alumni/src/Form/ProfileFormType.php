<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            ['label'=>'Username']
        )->add(
            'firstname',
            TextType::class,
            ['label'=>'First Name']
        )->add(
            'lastname',
            TextType::class,
            ['label'=>'Last Name']
        )->add(
            'profilePicture',
            FileType::class,
            ['label'=>'Picture',
            'required' => false]
        );;
        
        if ($options['standalone']) {
            $builder->add(
                'Update', 
                SubmitType::class,
                ['attr'=>['class'=> 'btn btn-dark']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'standalone' => false
        ]);
    }
}
