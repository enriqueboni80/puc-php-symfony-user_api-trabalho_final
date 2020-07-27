<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Telephone;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Message\ListUserMessage;
use App\Message\CreateUserMessage;
use App\Message\RemoveUserMessage;
use App\Message\UpdateUserMessage;
use App\Message\DetailUserMessage;


class UserController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(\Symfony\Component\Messenger\MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/users", methods={"GET"})
     */
    public function listAction(): Response
    {
        try {
            $handlerResult = $this->bus->dispatch(new ListUserMessage());
            $users = $handlerResult->last(HandledStamp::class)->getResult();
            $data = [];
            foreach ($users as $user) {
                $data[] = $this->userToArray($user);
            }
            return new JsonResponse($data, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return new JsonResponse($th, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     */
    public function detailAction(int $id): Response
    {
        try {
            $handlerResult = $this->bus->dispatch(new DetailUserMessage($id));
            $user = $handlerResult->last(HandledStamp::class)->getResult();
            return new JsonResponse($this->userToArray($user));
        } catch (\Throwable $th) {
            return new JsonResponse($th, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        try {
            $handlerResult = $this->bus->dispatch(new CreateUserMessage($request));
            $user = $handlerResult->last(HandledStamp::class)->getResult();
            return new Response('', Response::HTTP_CREATED, [
                'Location' => '/users/' . $user->getId()
            ]);
        } catch (\Throwable $th) {
            return new JsonResponse($th, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, int $id): Response
    {
        try {
            $handlerResult = $this->bus->dispatch(new UpdateUserMessage($request, $id));
            $user = $handlerResult->last(HandledStamp::class)->getResult();
            return new Response('', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return new JsonResponse($th, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function removeAction(int $id): Response
    {
        try {
            $this->bus->dispatch(new RemoveUserMessage($id));
            return new Response('', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return new JsonResponse($th, Response::HTTP_BAD_REQUEST);
        }
    }

    private function userToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'telephones' => array_map(fn (Telephone $telephone) => [
                'number' => $telephone->getNumber()
            ], $user->getTelephones()->toArray())
        ];
    }
}
