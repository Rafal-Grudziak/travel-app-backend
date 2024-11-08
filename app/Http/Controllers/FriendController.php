<?php

namespace App\Http\Controllers;

use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\ProfileBasicResource;
use App\Http\Responses\PaginatedResponse;
use App\Models\Friend;
use App\Models\User;
use App\Services\FriendService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


#[OA\Tag(name: "Friends")]
class FriendController extends BaseController
{
    #[OA\Post(
        path: '/api/friends/send-request/{user}',
        description: 'Send a friend request to a user.',
        summary: 'Send friend request',
        security: [['sanctum' => []]],
        tags: ['Friends'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'ID of the user to send a friend request to',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Friend request sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Friend request sent.')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request - possibly already sent request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Request already sent or invalid user.')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad Request',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Friend request already exists.')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            )
        ]
    )]
    public function send(Request $request, $receiverId, FriendService $friendRequestService): JsonResponse
    {
        $receiver = User::findOrFail($receiverId);

        if(!$friendRequestService->sendFriendRequest(auth()->user(), $receiver)) {
            return response()->json(['message' => 'Friend request already exists.'], 400);
        }

        return response()->json(['message' => 'Friend request sent.']);
    }

    #[OA\Post(
        path: '/api/friends/accept-request/{friend_request}',
        description: 'Accept a pending friend request.',
        summary: 'Accept friend request',
        security: [['sanctum' => []]],
        tags: ['Friends'],
        parameters: [
            new OA\Parameter(
                name: 'friend_request',
                description: 'ID of the friend request to be accepted.',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Friend request accepted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Friend request accepted.')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request - friend request already accepted or not pending',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request or already accepted.')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            )
        ]
    )]
    public function accept(Request $request, $requestId, FriendService $friendRequestService): JsonResponse
    {
        $friendRequest = Friend::findOrFail($requestId);
        if(!$friendRequestService->acceptFriendRequest($friendRequest)) {
            return response()->json(['message' => 'Error occured.'], 400);
        }

        return response()->json(['message' => 'Friend request accepted.']);
    }

    #[OA\Post(
        path: '/api/friends/reject-request/{friend_request}',
        description: 'ID of the friend request to be rejected.',
        summary: 'Reject friend request',
        security: [['sanctum' => []]],
        tags: ['Friends'],
        parameters: [
            new OA\Parameter(
                name: 'friend_request',
                description: 'ID of the user whose friend request is to be rejected',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Friend request rejected successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Friend request rejected.')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request - friend request already rejected or invalid',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid request or already rejected.')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            )
        ]
    )]
    public function reject(Request $request, $requestId, FriendService $friendRequestService): JsonResponse
    {
        $friendRequest = Friend::findOrFail($requestId);

        if(!$friendRequestService->rejectFriendRequest($friendRequest)) {
            return response()->json(['message' => 'Error occured.'], 400);
        }

        return response()->json(['message' => 'Friend request rejected.']);
    }

    #[OA\Get(
        path: '/api/friends/list',
        description: 'Get a list of users friends.',
        summary: 'Get a list of users friends.',
        security: [['sanctum' => []]],
        tags: ['Friends'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved friends list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'user', properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                        new OA\Property(property: 'avatar', type: 'string', example: 'https://example.com/avatar.jpg'),
                                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer')
                                    ],
                                        type: 'object'
                                    )
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 1),
                                new OA\Property(property: 'per_page', type: 'integer', example: 10),
                                new OA\Property(property: 'total', type: 'integer', example: 3)
                            ],
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            )
        ]
    )]
    public function list(Request $request, FriendService $friendRequestService): JsonResponse
    {
        $friends = auth()->user()->friends()->paginate(10);

        return PaginatedResponse::format($friends, ProfileBasicResource::class);
    }

    #[OA\Get(
        path: '/api/friends/requests',
        description: 'Get a list of pending friend requests.',
        summary: 'Get pending friend requests',
        security: [['sanctum' => []]],
        tags: ['Friends'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully retrieved pending friend requests',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'user', properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                        new OA\Property(property: 'avatar', type: 'string', example: 'https://example.com/avatar.jpg'),
                                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer')
                                    ],
                                        type: 'object'
                                    )
                                ]
                            )
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 1),
                                new OA\Property(property: 'per_page', type: 'integer', example: 10),
                                new OA\Property(property: 'total', type: 'integer', example: 3)
                            ],
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            )
        ]
    )]
    public function requests(Request $request, FriendService $friendRequestService): JsonResponse
    {
        $pendingRequests = $friendRequestService->getPendingRequests(auth()->user());

        return PaginatedResponse::format($pendingRequests, FriendRequestResource::class);
    }

}
