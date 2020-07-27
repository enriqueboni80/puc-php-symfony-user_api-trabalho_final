<?php

namespace App\MessageHandler;

use App\Message\CreateUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    public function __invoke(CreateUserMessage $message)
    {
        // do something with your message
    }
}
