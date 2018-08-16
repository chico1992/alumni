<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            TextType::class,
            ['label'=>'Please choose a Username']
        )->add(
            'firstname',
            TextType::class,
            ['label'=>'Please enter your Firstname']
        )->add(
            'lastname',
            TextType::class,
            ['label'=>'Please enter your Lastname']
        );
        
    if ($options['standalone']) {
        $builder->add(
            'submit', 
            SubmitType::class,
            ['attr'=>['class'=>'btn-success btn-block']]
        );
    }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
