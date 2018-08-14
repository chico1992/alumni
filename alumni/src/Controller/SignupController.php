<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class SignupController extends Controller
{
    public function signup(Request $request, EncoderFactoryInterface $factory)
    {   
        // build the form
        $user = new User();
        $form = $this->createForm(
            UserFormType::class,
            $user,
            ['standalone' => true]
        );

        // handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            
            // insert data in database

            // encode the password 
            $encoder = $factory->getEncoder(User::class);
            
            $encodedPassword = $encoder->encodePassword(
                $user->getPassword(),
                $user->getUsername()
            );
            $user->setPassword($encodedPassword);

            // save the User!
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render(
            'base.html.twig',
            ['user_form' => $form->createView()]
        );
    }
}

?>