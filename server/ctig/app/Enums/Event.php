<?php

namespace App\Enums;

enum Event:string
{
    case Access = 'access';
    case Export = 'export'; 
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
    case Generated = 'generated';
    case Login = 'login';
    case Logout = 'logout';
    case PasswordReseted = 'password_reseted';
    case PasswordChanged = 'password_changed';
}
