<?php

namespace App\Infrastructure\Framework\Http\Controllers\User;

use App\Domain\Factory\UserFactory;
use App\Domain\Repository\UserRepository;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use App\Infrastructure\Framework\Http\Requests\UserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SaveDetailController extends Controller
{
    private $userRepository;
    private $userFactory;
    private $normalizer;

    public function __construct(
        UserRepository $userRepository,
        UserFactory $userFactory,
        NormalizerInterface $normalizer
    )
    {
        $this->userFactory = $userFactory;
        $this->userRepository = $userRepository;
        $this->normalizer = $normalizer;
    }

    public function __invoke(Request $request, ?int $userId = null): JsonResponse
    {;
        $user = $this->userFactory->getUser(
            $userId,
            $request->name,
            $request->email,
            $request->phone,
            $request->address
        );

        try {

            return new JsonResponse(
                $this->normalizer->normalize(
                    $this->userRepository->save($user),
                    null,
                    ['groups' => ['userData']]
                ),
                    200
                );
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), 404);
        }
    }
}
