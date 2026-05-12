<?php

namespace App\Support;

final class AppMiddleware{
    public const string LOG_CONTEXT = 'log.context';
    public const string USER_ACTIVE = 'user.active';
    public const string CENTER_ACTIVE = 'center.active';
    public const string REQUEST_TIME_MEASURE = 'request.time.measure';
    public const string HAS_CHANGE_PASSWORD = 'has.change.password';
    public const string USER_HAS_ANY_ROLE = 'user.has.any.role';
    public const string CENTER_CONTEXT = 'center.context';
}