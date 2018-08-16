<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSearchFormType;
use App\DTO\UserSearch;
use App\Entity\User;


class DefaultController extends Controller
{
    public function homepage()
    {
        return $this->render('Default/homepage.html.twig');
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
        $searchForm = $this->createForm(UserSearchFormType::class, $dto, ['standalone'=>true]);

        $searchForm->handleRequest($request);

        $users = $manager->getRepository(User::class)->findByUserSearch($dto);
    
        return $this->render(
            'Userlist/list.html.twig',
            [
                'users' =>  $users,
                'searchForm'=>$searchForm->createView()

            ]
        );
    }

}