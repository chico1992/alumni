<?php
namespace App\Form;

use App\Entity\VisibiltyGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'label',
                TextType::class,
                [ 'label' => "Name of the new group:" ]
            );

        if ($options['standalone']) {
            $builder->add(
                'submit', 
                SubmitType::class, 
                ['attr' => ['class' => 'btn-success btn-block']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VisibiltyGroup::class,
            'standalone' => false
        ]);
    }
}
