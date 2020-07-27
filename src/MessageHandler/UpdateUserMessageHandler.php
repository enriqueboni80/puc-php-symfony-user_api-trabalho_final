<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\UpdateUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;


final class UpdateUserMessageHandler implements MessageHandlerInterface
{

    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function __invoke(UpdateUserMessage $message)
    {
        $requestContent = $message->getRequest()->getContent();
        $json = json_decode($requestContent, true);
        

        $user = $this->manager->getRepository(User::class)->find($message->getUserId());

        if (null === $user) {
            throw new \InvalidArgumentException('User with ID #' . $message->getUserId() . ' not found');
        }

        $user->setName($json['name']);
        $user->setEmail($json['email']);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $violations = array_map(fn (ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ], iterator_to_array($errors));
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }

        $this->manager->persist($user);
        $this->manager->flush();

        return $user;
    }
}
