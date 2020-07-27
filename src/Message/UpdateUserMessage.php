<?php

namespace App\Message;

final class UpdateUserMessage
{

    private $request;
    private $id;

    public function __construct($request, int $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getUserId()
    {
        return $this->id;
    }
}
