<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\ProfileFormType;
use App\Entity\Document;


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

            $file = $user->getProfilePicture();

            if($file){
                $document = new Document();
                $document->setId($file->getId())
                    ->setPath($this->getPath('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getName());
                $file->move($this->getPath('upload_dir'));
                
                $user->setProfilePicture($document);
                $manager->persist($document);
            }
            
            $manager->flush();
            
            return $this->redirectToRoute('profile_edit', ['user' => $user->getUser()]);
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