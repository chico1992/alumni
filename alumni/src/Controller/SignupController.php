<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class SignupController extends Controller
{
    public function signup(Request $request, EncoderFactoryInterface $factory, string $invitationId)
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
            $repository = $this->getDoctrine()->getRepository(Invitation::class);
            $invitation = $repository->findOneBy(['id' => $invitationId]);
            if(!$invitation)
            {
                // insert data in database

                // encode the password 
                $encoder = $factory->getEncoder(User::class);
                
                $encodedPassword = $encoder->encodePassword(
                    $user->getPassword(),
                    $user->getUsername()
                );
                $user->setPassword($encodedPassword);

                // search for the roles of the user, which where set up in Invitation
                //$invitation = $repository->findBy($invitationId);
                $roles = $invitation->getRoles();
                foreach($role as $roles)
                {
	                $user->addRole($role);
                }

                // search for the visibilityGroups of the user, which where set up in Invitation
                $groups = $invitation->getVisibilityGroups();
                foreach($group as $groups)
                {
                    $user->addVisibilityGroup($group);
                }


                // save the User!
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
            }
            
           
        }

        return $this->render(
            'Default/signup.html.twig',
            ['user_form' => $form->createView()]
        );
    }
}

?>