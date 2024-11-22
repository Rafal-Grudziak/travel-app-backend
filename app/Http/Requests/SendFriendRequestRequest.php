<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendFriendRequestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value === auth()->id()) {
                        $fail('You cannot send a friend request to yourself.');
                    }
                },
            ],
        ];
    }
}
