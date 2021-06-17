<?php

namespace App\Infrastructure\Framework\Http\Controllers\User;

use Illuminate\Http\JsonResponse;
use App\Application\Query\UserQuery;
use App\Infrastructure\Framework\Http\Controllers\Controller;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserDetailController extends Controller
{
    private $userQuery;
    private $normalizer;

    public function __construct(
        NormalizerInterface $normalizer,
        UserQuery $userQuery
    ) {
        $this->normalizer = $normalizer;
        $this->userQuery = $userQuery;
    }

    public function __invoke(int $userId): JsonResponse
    {
        try {
            return new JsonResponse(
                $this->normalizer->normalize(
                    $this->userQuery->getDetail($userId),
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
