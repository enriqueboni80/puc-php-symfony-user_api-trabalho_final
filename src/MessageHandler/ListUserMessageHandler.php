<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\ListUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


final class ListUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(ListUserMessage $message)
    {
        return $this->manager->getRepository(User::class)->findAll();
    }
}
