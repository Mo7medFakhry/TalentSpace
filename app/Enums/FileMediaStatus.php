<?php

namespace App\Enums;

enum FileMediaStatus:string
{
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case Rejected = 'rejected';
}











