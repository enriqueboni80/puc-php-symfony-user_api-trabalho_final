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


    /*
     * Add whatever properties & methods you need to hold the
     * data for this message class.
     */

//     private $name;
//
//     public function __construct(string $name)
//     {
//         $this->name = $name;
//     }
//
//    public function getName(): string
//    {
//        return $this->name;
//    }
}
