<?php

namespace App\Enums;

enum FriendStatus: string
{
    case REQUEST_PENDING = 'request_pending';
    case REQUEST_ACCEPTED = 'request_accepted';

}
