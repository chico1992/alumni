<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function getCurrentUser(Request $request)
    {
        $user = $this->getUser();
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $user,
            'json', 
            array(
                'groups' => array('user')
            )
        );
        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }

    public function getCurrentUserConversations(Request $request)
    {
        $user = $this->getUSer();
        $conversations = $user->getConversations();
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $conversations,
            'json', 
            array(
                'groups' => array('conversation')
            )
        );
        return new JsonResponse(
            $data,
            200,
            [],
            true
        );
    }

    public function getSerializer() : SerializerInterface
    {
        return $this->get('serializer');
    }
}