<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\VisibilityGroup;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user= $options['user'];
        $builder
            ->add(
                'title',
                TextType::class,
                ['label' => 'Titel for your post']
            )
            ->add(
                'content',
                TextareaType::class,
                ['label' => 'Enter the content of your post']
            )
            ->add(
                'status',
                ChoiceType::class,
                array(
                    'choices' => array(
                        'publish' => true,
                        'draft' => false,
                    )
                )
            )
            ->add(
                'visibility',
                EntityType::class,
                array(
                    'class' => VisibilityGroup::class,
                    'choices' => $user->getVisibilityGroups(),
                    'choice_label' => 'label'
                )
            )
        ;
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
            'data_class' => Post::class,
            'standalone' => false,
            'user' => null
        ]);
    }
}
