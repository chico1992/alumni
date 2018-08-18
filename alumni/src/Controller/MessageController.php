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
    public function receiveMessage(Request $request, Conversation $conversation,MessageSender $messageSender)
    {
        $message = new Message();
        $content = $request->request->get('content');
        if($content)
        {
            $message->setContent($content);
            $message->setSender($this->getUser());
            $message->setReceiver($conversation);
            $manager = $this->getDoctrine()->getManager();
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
            "message was sent",
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