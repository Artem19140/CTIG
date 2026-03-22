<?php


namespace App\Http\Dto;

use Carbon\Carbon;

final readonly class ExamDto{
    public function __construct(
        public Carbon $beginTime,
        public int $addressId,
        public int $examTypeId,
        public string $comment,
        public array $examiners
    ){}
}