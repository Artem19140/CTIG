<?php

namespace App\Support;

final class AppMiddleware{
    public const string LOG_CONTEXT = 'log.context';
    public const string EMPLOYEE_ACTIVE = 'employee.active';
    public const string CENTER_ACTIVE = 'center.active';
    public const string REQUEST_TIME_MEASURE = 'request.time.measure';
    public const string HAS_CHANGE_PASSWORD = 'has.change.password';
    public const string EMPLOYEE_HAS_ANY_ROLE = 'employee.has.any.role';
    public const string CENTER_CONTEXT = 'center.context';
    public const string ENSURE_ATTEMPT_VALID_STATUS = 'ensure.attempt.valid.status';
}