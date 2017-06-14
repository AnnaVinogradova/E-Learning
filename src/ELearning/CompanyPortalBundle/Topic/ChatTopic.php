<?php

/**
 * Created by PhpStorm.
 * User: anna
 * Date: 6/01/17
 * Time: 8:08 PM
 */

namespace ELearning\CompanyPortalBundle\Topic;

use ELearning\CompanyPortalBundle\Entity\Message;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;

use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;

use Gos\Bundle\WebSocketBundle\Client\ClientStorageInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
//use Blogger\BlogBundle\Entity\Post;

class ChatTopic implements TopicInterface
{
    protected $em;
    protected $clientManipulator;

     /**
     * @param \Doctrine\ORM\EntityManager $em
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct($em, ClientManipulatorInterface $clientManipulator)
    {
        $this->em = $em;
        $this->clientManipulator = $clientManipulator;
    }

    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
            $user = $this->clientManipulator->getClient($connection);
            //$posts = $this->em->getRepository('BloggerBlogBundle:Post')->findOneById(1);
            //$name = $posts->getTitle();

            $id = $connection->resourceId;
            $topic_id = $topic->getId();
            //$user = $this->clientStorage->getClient($connection->WAMP->clientStorageId);
            //this will broadcast the message to ALL subscribers of this topic.
            $topic->broadcast(['msg' =>  "User " . $user . " open chat"]);
    }

    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
            //this will broadcast the message to ALL subscribers of this topic.
            $user = $this->clientManipulator->getClient($connection);

            $topic->broadcast(['msg' => "User " . $user . " close chat"]);
        }

    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $user = $this->clientManipulator->getClient($connection);

        $resource = $topic->getId();

        $pos = strrpos($resource, '/', -1);
        $id = substr($resource, $pos+1);

        $flow = $this->em->getRepository('PortalBundle:Flow')->findOneById($id);

        $text = $event;
        $message = new Message();
        $message->setText($text);
        $message->setChat($flow->getChat());
        $message->setUser($user);

        $this->em->persist($message);
        $this->em->flush();

        $topic->broadcast([
            'msg' => 'User ' . $user . ' wrote: ' . $event
        ]);
    }

    public function getName()
    {
            return 'topic.chat';
    }

}