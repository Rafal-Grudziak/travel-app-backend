<?php

namespace App\Services;

use App\Enums\FriendStatus;
use App\Models\Friend;
use App\Models\User;

class FriendService extends ModelService
{
    protected function getModelClass(): string
    {
        return Friend::class;
    }

    public function sendFriendRequest(User $sender, int $receiverId): ?Friend
    {
        $receiver = User::find($receiverId);

        if(!$this->checkIfRequestExists($sender, $receiver))
        {
            $friendRequest = $this->getModel()->newInstance();

            $friendRequest->setAttribute('sender_id', $sender->id);
            $friendRequest->setAttribute('receiver_id', $receiver->id);
            $friendRequest->setAttribute('status', FriendStatus::REQUEST_PENDING);

            $friendRequest->save();

            return $friendRequest;
        }

        return null;
    }

    public function acceptFriendRequest(Friend $friendRequest): bool
    {
        if($friendRequest->status === FriendStatus::REQUEST_PENDING)
        {
            $friendRequest->setAttribute('status', FriendStatus::REQUEST_ACCEPTED);
            $friendRequest->save();
            return true;
        }
        return false;
    }

    public function deleteFriend(int $friendId): bool
    {
        $user = auth()->user();

        $friend = $this->getModel()
            ->where(function($query) use ($user, $friendId) {
                $query->where('sender_id', $user->id)->where('receiver_id', $friendId);
            })->orWhere(function($query) use ($user, $friendId) {
                $query->where('sender_id', $friendId)->where('receiver_id', $user->id);
            })->where('status', FriendStatus::REQUEST_ACCEPTED)->first();

        if($friend)
        {
            $friend->delete();
            return true;
        }
        return false;
    }

    public function getPendingRequests(User $user)
    {
        return $this->getModel()
            ->where('receiver_id', $user->id)
            ->where('status', FriendStatus::REQUEST_PENDING)
            ->with('sender')
            ->paginate(10);
    }

    public function checkIfRequestExists(User $sender, User $receiver): bool
    {
        return $this->getModel()
            ->where(function($query) use ($sender, $receiver) {
                $query->where('sender_id', $sender->id)->where('receiver_id', $receiver->id);
            })->orWhere(function($query) use ($sender, $receiver) {
                $query->where('sender_id', $receiver->id)->where('receiver_id', $sender->id);
            })->exists();
    }
}
