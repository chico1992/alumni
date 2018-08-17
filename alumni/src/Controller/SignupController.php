<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Invitation;

class SignupController extends Controller
{
    public function signup(Request $request, EncoderFactoryInterface $factory, Invitation $invitation)
    {   
        // build the form
        $user = new User();
        $form = $this->createForm(
            UserFormType::class,
            $user,
            ['standalone' => true]
        );

        $errorInvitation = false;
        $errorEmail = false;

        // handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {   
            $userEmail = $form["email"]->getData();

            if(empty($invitation))
            {
                $errorInvitation = true;
                $errorEmail = false;
            }
            else if(($userEmail != $invitation->getEmail()) )
            {
                $errorInvitation = false;
                $errorEmail = true;
            }
            else
            {   
              
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
                foreach($roles as $role)
                {
	                $user->addRole($role);
                }

                // search for the visibilityGroups of the user, which where set up in Invitation
                $groups = $invitation->getVisibilityGroups();
                foreach($groups as $group)
                {
                    $user->addVisibilityGroup($group);
                }

                // save the User! and delete the invitation
                // insert data in database
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->remove($invitation);
                $manager->flush();
            }
            
        }

        return $this->render(
            'Default/signup.html.twig',
            [
                'user_form' => $form->createView(),
                'errorInvitation' => $errorInvitation,
                'errorEmail' => $errorEmail
            ]
        );
    }
}

?>