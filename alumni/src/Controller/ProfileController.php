<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\ProfileFormType;


class ProfileController extends Controller{

    public function profile()
    {
        $user = $this->getUser();

        return $this->render('Default/profile.html.twig', ['user' => $user]
        );
    }

    public function profileEdit(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $profileForm = $this->createForm(ProfileFormType::class, $user, ['standalone' => true]);
        $profileForm->handleRequest($request);
        
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            
            $manager->flush();
            
            return $this->redirectToRoute('profile_edit', ['user' => $this->getUser()]);
        }
        
        return $this->render(
            'Default/profileEdit.html.twig',
            [
                'user'=>$user,
                'profileForm' => $profileForm->createView()
            ]
        );
    }
}