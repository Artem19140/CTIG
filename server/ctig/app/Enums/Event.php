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
    case Started = 'started';
    case Finished = 'finished';
    case Checked = 'checked';
    case Login = 'login';
    case Logout = 'logout';
    case PasswordReset = 'password_reset';
    case PasswordChanged = 'password_changed';
}
