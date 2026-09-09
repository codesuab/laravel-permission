<?php
namespace Codesuab\Permission\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;
class PermissionDeniedException extends HttpException { public function __construct(string $permission){parent::__construct(403,'Permission denied: '.$permission);} }
