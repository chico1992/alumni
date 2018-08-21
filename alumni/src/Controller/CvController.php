<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\CvFormType;
use App\Entity\Cv;
use App\Entity\Document;

class CvController extends Controller{

    public function cvEdit(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        
        $cvForm = $this->createForm(CvFormType::class, $user, ['standalone' => true]);
        $cvForm->handleRequest($request);
        
        if ($cvForm->isSubmitted() && $cvForm->isValid()) {

            $file = $user->getCv();

            if($file){
                $cv = new Cv();
                $cv->setDocument($file->getDocument())
                    ->setStatus($file->getStatus());
                $file->move($this->getParameter('upload_dir'));
                
                $user->setCv($cv);
                $manager->persist($cv);
            }
            
            $manager->flush();
            
            return $this->redirectToRoute('profile');
        }
        
        return $this->render(
            'Default/cv.html.twig',
            [
                'cvForm' => $cvForm->createView()
            ]
        );
    }
}