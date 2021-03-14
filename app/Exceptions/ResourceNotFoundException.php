<?php

namespace App\Exceptions;

use Flugg\Responder\Exceptions\Http\HttpException;

/**
 * An exception thrown whan a resource is not found.
 * Class ResourceNotFoundException
 * @package App\Exceptions
 */
class ResourceNotFoundException extends HttpException
{
    /**
     * An HTTP status code.
     *
     * @var int
     */
    protected $status = 404;

    /**
     * An error code.
     *
     * @var string|null
     */
    protected $errorCode = 'resource_not_found';
}
