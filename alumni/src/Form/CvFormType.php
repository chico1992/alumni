<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CvFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'cv',
                FileType::class,
                ['label'=>'Please upload your CV'],
                ['required' => false]
            )
            /* ->add(
                'status',
                CheckboxType::class,
                ['label'=>'Show your CV publicly?'],
                ['required' => false]
            ) */;

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
            'standalone' => false
        ]);
    }
}
