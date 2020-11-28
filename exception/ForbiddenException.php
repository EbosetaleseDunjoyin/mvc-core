<?php


namespace edj\mvcframecore\exception;


class ForbiddenException extends  \Exception
{
    protected $message = 'You don\'t have permission to this page. Login to access this page';
    protected $code = 403;
}