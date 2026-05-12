<?php

namespace App\Exceptions;

use Illuminate\Contracts\Debug\ShouldntReport;

class BusinessException extends BaseException implements ShouldntReport
{
}
