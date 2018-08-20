<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Document;
use App\Form\PictureFormType;
use Symfony\Component\HttpFoundation\File\File;


class PictureController extends Controller{

    public function pictureEdit(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->setProfilePicture( new File($this->getParameter('upload_dir') . '/' . $user->getProfilePicture()->getName()));
        $pictureForm = $this->createForm(PictureFormType::class, $user, ['standalone' => true]);
        $pictureForm->handleRequest($request);
        
        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {

            $file = $user->getProfilePicture();

            if($file){
                $document = new Document();
                $document->setPath($this->getParameter('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getFilename());
                $file->move($this->getParameter('upload_dir'));
                
                $user->setProfilePicture($document);
                $manager->persist($document);
            }
            
            $manager->flush();
            
            return $this->redirectToRoute('profile');
        }
        
        return $this->render(
            'Default/picture.html.twig',
            [
                'pictureForm' => $pictureForm->createView()
            ]
        );
    }
}