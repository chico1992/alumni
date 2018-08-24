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
        $manager = $this->getDoctrine()->getManager();
        $userActions = false;

        if ($user == $this->getUser())
        {
            $userActions = true;
        }

        $cv = $manager->getRepository(Cv::class)->findOneBy(['user' => $user->getId()]);
        if($cv)
        {
            $document = $cv->getDocument();
        } else {
            $document = null;
        }
        return $this->render(
            'Default/profile.html.twig',
            [
                'user' => $user,
                'cv' => $document,
                'userActions' => $userActions

            ]
        );
    }


}