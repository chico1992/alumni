<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Comment;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
                'content',
                TextareaType::class,
                ['label'=>'Please enter your comment']
          
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
        $resolver->setDefaults(
            [
                'data_class' => Comment::class,
                'standalone' => false
            ]
        );
    }
}