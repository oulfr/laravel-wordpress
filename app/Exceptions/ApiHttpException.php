<?php

namespace App\Exceptions;

use Flugg\Responder\Exceptions\Http\HttpException;

class ApiHttpException extends HttpException
{
    /**
     * Construct the exception class.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    public function __construct($status = 500, string $errorCode = null, string $message = null, array $headers = null)
    {
        parent::__construct($message, $headers);
        $this->status = $status;
        $this->errorCode = $errorCode;
    }
}
