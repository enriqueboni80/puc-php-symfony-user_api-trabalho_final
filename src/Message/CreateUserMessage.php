<?php

namespace App\Message;

final class CreateUserMessage
{

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
