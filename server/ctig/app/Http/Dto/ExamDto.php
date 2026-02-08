<?php


namespace App\Http\Dto;

use DateTime;

final readonly class ExamDto{
    public function __construct(
        public DateTime $beginTime,
        public int $addressId,
        public int $capacity,
        public int $examTypeId,
        public string $comment,
        public array $testers
    ){}
}