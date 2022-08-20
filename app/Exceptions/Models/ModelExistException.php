<?php

namespace App\Exceptions\Models;

class ModelExistException extends \Exception
{
public function __construct(string $message = "Model is already exist", int $code = 0, ?Throwable $previous = null)
{
    parent::__construct($message, $code, $previous);
}
}
