<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSearchFormType;
use App\DTO\UserSearch;
use App\Entity\User;
use App\Entity\Cv;


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
        $userid = $user->getId();
        $manager = $this->getDoctrine()->getManager();

        $cv = $manager->getRepository(Cv::class)->findOneBy(['user' => $userid]);
        if($cv)
        {
            $document = $cv->getDocument();
        } else {
            $document = null;
        }
        return $this->render(
            'default/profileSearch.html.twig',
            [
                'searchedUser' => $user,
                'cv' => $document
            ]
        );
    }


}