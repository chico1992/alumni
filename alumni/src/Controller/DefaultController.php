<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function homepage()
    {
        return $this->render('default/homepage.html.twig');
    }



    public function login(AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render(
            'Default/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            )
        );
    }

    public function listUsers(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
    
        $dto = new UserSearch();
        $searchForm = $this->createForm(TaskSearchFormType::class, $dto, ['standalone'=>true]);

        $searchForm->handleRequest($request);

        $tasks = $manager->getRepository(Task::class)->findByTaskSearch($dto);
    
        return $this->render(
            'task/list.html.twig',
            [
                'tasks' =>  $tasks,
                'form' => $form->createView(),
                'searchForm'=>$searchForm->createView()

            ]
        );
    }

}