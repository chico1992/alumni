<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use App\Entity\Conversation;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class ConversationController extends Controller
{
    public function newConversation(User $user)
    {
        $conv = new Conversation();
        $exists = false;
        $thisUser = $this->getUser();
        $userConversations = $thisUser->getConversations();
        foreach ($userConversations as $conversation) {
            $conversationUsers = $conversation->getUsers();
            if($conversationUsers->contains($user) && $conversationUsers->count()==2)
            {
                $exists = true;
                $conv = $conversation; 
            }
        }
        if(!$exists)
        {
            $conv->addUser($user)->addUser($thisUser);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($conv);
            $manager->flush();
        }
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $conv,
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