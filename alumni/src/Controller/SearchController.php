<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSearchFormType;
use App\DTO\UserSearch;
use App\Entity\User;


class SearchController extends Controller
{

public function listUsers(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
    
        $dto = new UserSearch();
        $searchForm = $this->createForm(UserSearchFormType::class, $dto, ['standalone'=>true]);

        $searchForm->handleRequest($request);

        $users = $manager->getRepository(User::class)->findByUserSearch($dto);
    
        return $this->render(
            'Search/searchList.html.twig',
            [
                'users' =>  $users,
                'searchForm'=>$searchForm->createView()

            ]
        );
    }

    public function showUser(User $user)
    {
        return $this->render(
            'default/profileSearch.html.twig',
            [
                'searchedUser' => $user
            ]
        );
    }


}