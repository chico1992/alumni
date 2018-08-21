<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Service\MessageSender;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class MessageController extends Controller
{
    public function receiveMessage(Request $request,MessageSender $messageSender)
    {
        $message = new Message();
        $content = $request->request->get('content');
        $conversationId = $request->request->get('conversation');
        if($content && $conversationId)
        {
            $manager = $this->getDoctrine()->getManager();
            $conversation = $this->getDoctrine()
                ->getRepository(Conversation::class)
                ->find($conversationId);
            $message->setContent($content);
            $message->setSender($this->getUser());
            $message->setReceiver($conversation);
            $manager->persist($message);
            $manager->flush();
        }
        $serializer = $this->getSerializer();
        $data = $serializer->serialize(
            $message,
            'json', 
            array(
                'groups' => array('message')
            )
        );
        $messageSender->sendMessage($data);
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