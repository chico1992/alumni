<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\ProfileFormType;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Document;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


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

        $picture = $user->getProfilePicture();
        if($picture)
        {
            $file = new File($picture->getPath() . '/' . $picture->getName());
            $user->setProfilePicture($file);
        }

        $profileForm = $this->createForm(ProfileFormType::class, $user, ['standalone' => true]);
        $profileForm->handleRequest($request);
        
        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            
            $file = $user->getProfilePicture();
            if($file){
                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFilename());
                $file->move($this->getParameter('upload_dir'));
                
                $user->setProfilePicture($document);
                
                $manager->persist($document);
                $manager->remove($picture);
            }
            else
            {
                $user->setProfilePicture($picture);
            }

            $manager->flush();
            
            return $this->redirectToRoute('profile');
        }
        /*
        very important!
        */
        $user->setProfilePicture($picture);
        
        return $this->render(
            'Default/profileEdit.html.twig',
            [
                'user'=>$user,
                'profileForm' => $profileForm->createView()
            ]
        );
    }

    public function downloadPicture(Document $document)
    {
        $fileName = sprintf(
            '%s/%s',
            $document->getPath(),
            $document->getName()
            );

        return new BinaryFileResponse($fileName);
    }
}