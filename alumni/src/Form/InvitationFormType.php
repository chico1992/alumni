<?php

namespace App\Form;

use App\Entity\Invitation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Role;
use App\Entity\VisibilityGroup;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvitationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'email',
                EmailType::class
            )
            ->add(
                'roles',
                EntityType::class,
                array(
                    'class' => Role::class,
                    'choice_label' => 'label',
                    'expanded' => true,
                    'multiple' => true
                )
            )
            ->add(
                'visibilityGroups',
                EntityType::class,
                array(
                    'class' => VisibilityGroup::class,
                    'choice_label' => 'label',
                    'expanded' => false,
                    'multiple' => true
                )
            )
        ;
        if($options['standalone']){
            $builder->add(
                'submit',
                SubmitType::class,
                ['attr' => ['class' => 'btn btn-lg btn-dark btn-block']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invitation::class,
            'standalone' => false
        ]);
    }
}
