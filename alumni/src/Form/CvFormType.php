<?php

namespace App\Form;

use App\Entity\Cv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CvFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'document',
                FileType::class,
                ['label'=>'Upload your CV here (.PDF files only)',
                'required' => true]
            
            )->add(
                'status',
                ChoiceType::class,
                array(
                    'choices' => array(
                        'Public' => true,
                        'Private' => false,
                    )
                )
            )
        ;

        if ($options['standalone']) {
            $builder->add(
                'submit', 
                SubmitType::class, 
                ['attr' => ['class' => 'btn btn-dark']]
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,
            'standalone' => false
        ]);
    }
}
