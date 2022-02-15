<?php

namespace mg\FrameworkPhpMvcCore\exception;

class NotFoundException extends \Exception
{
    protected $message = 'NOT FOUND THIS PAGE';
    protected $code = 404;
}