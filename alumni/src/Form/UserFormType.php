<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
                'username',
                TextType::class,
                ['label'=>'Username']
            )->add(
                'email',
                EmailType::class,
                ['label'=>'Email adress (where your invitation was sent to)']
            )->add(
                'password',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields should match.',
                    'first_options'  => array('label' => 'Password (minimum 8 characters)'),
                    'second_options' => array('label' => 'Repeat Password')
                ]
            )->add(
                'firstname',
                TextType::class,
                ['label'=>'Firstname']
            )->add(
                'lastname',
                TextType::class,
                ['label'=>'Lastname']
            );
            
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
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'standalone' => false
            ]
        );
    }
}