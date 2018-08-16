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

    public function profileEdit(User $user, Request $request)
    {
        $profileForm = $this->createForm(ProfileFormType::class, $user, ['standalone' => true]);
        $profileForm->handleRequest($request);
        
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('profile_edit', ['user' => $user->getUser()]);
        }
        
        return $this->render(
            'Default/profileEdit.html.twig',
            [
                'user'=>$user,
                'form' => $profileForm->createView()
            ]
        );
    }
}