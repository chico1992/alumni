<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Document;
use App\Form\PictureFormType;


class PictureController extends Controller{

    public function pictureEdit(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $picture = $this->getUser()->getProfilePicture();
        $pictureForm = $this->createForm(PictureFormType::class, $picture, ['standalone' => true]);
        $pictureForm->handleRequest($request);
        
        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {

            $file = $picture->getProfilePicture();

            if($file){
                $document = new Document();
                $document->setId($file->getId())
                    ->setPath($this->getPath('upload_dir'))
                    ->setMimeType($file->getMimeType())
                    ->setName($file->getName());
                $file->move($this->getPath('upload_dir'));
                
                $picture->setProfilePicture($document);
                $manager->persist($document);
            }
            
            $manager->flush();
            
            return $this->redirectToRoute('picture_edit', ['picture' => $this->getDocument()]);
        }
        
        return $this->render(
            'Default/picture.html.twig',
            [
                'picture'=>$picture,
                'pictureForm' => $pictureForm->createView()
            ]
        );
    }
}