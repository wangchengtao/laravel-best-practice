<?php


namespace App\Exceptions;

use App\Constants\BizCode;
use Exception as BaseException;
use Throwable;

class CustomException extends BaseException
{
    public function __construct(string $message = "", BizCode $code = BizCode::FAIL, Throwable $previous = null)
    {
        parent::__construct($message, $code->value, $previous);
    }

}
