<?php

namespace App\Exceptions;

class EntityNotFoundExсeption extends BaseException
{
    public function __construct(string $entity = ""){
        parent::__construct(422, "{$entity} не существует");
    }
}
