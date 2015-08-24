<?php
/**
 * GenericException Representation of generic exception
 */
class GenericException extends Exception
{
  /**
   * Constructor
   *
   * @param message[optional]
   * @param code[optional]
   * @param previous[optional]
   */
  public function __construct ($message = null, $code = null, $previous = null)
  {
    parent::__construct($message, $code, $previous);
  }
}
/**
* Parent class for all of the HTTP related exceptions.
* All HTTP status/error related exceptions should extend this class so
* catch blocks can be specifically typed.
*
*/
if (!class_exists('HttpException')) {
  class HttpException extends RuntimeException {
  }
}
/**
* Represents an HTTP 400 error.
*
*/
class BadRequestException extends HttpException {
/**
* Constructor
*
* @param string $message If no message is given 'Bad Request' will be the message
* @param string $code Status code, defaults to 400
*/
  public function __construct($message = null, $code = 400) {
      if (empty($message)) {
          $message = 'Bad Request';
      }
      parent::__construct($message, $code);
  }
}
/**
* Represents an HTTP 40error.
*/
class UnauthorizedException extends HttpException {
/**
* Constructor
*
* @param string $message If no message is given 'Unauthorized' will be the message
* @param string $code Status code, defaults to 401
*/
  public function __construct($message = null, $code = 401) {
      if (empty($message)) {
          $message = 'Unauthorized';
      }
      parent::__construct($message, $code);
  }
}
/**
* Represents an HTTP 403 error.
*/
class ForbiddenException extends HttpException {
/**
* Constructor
*
* @param string $message If no message is given 'Forbidden' will be the message
* @param string $code Status code, defaults to 403
*/
  public function __construct($message = null, $code = 403) {
      if (empty($message)) {
          $message = 'Forbidden';
      }
      parent::__construct($message, $code);
  }
}
/**
* Represents an HTTP 404 error.
*/
class NotFoundException extends HttpException {
/**
 * Constructor
 *
 * @param string $message If no message is given 'Not Found' will be the message
 * @param string $code Status code, defaults to 404
 */
    public function __construct($message = null, $code = 404) {
        if (empty($message)) {
            $message = 'Not Found';
        }
        parent::__construct($message, $code);
    }
}
/**
 * Represents an HTTP 405 error.
 */
class MethodNotAllowedException extends HttpException {
/**
 * Constructor
 *
 * @param string $message If no message is given 'Method Not Allowed' will be the message
 * @param string $code Status code, defaults to 405
 */
    public function __construct($message = null, $code = 405) {
        if (empty($message)) {
            $message = 'Method Not Allowed';
        }
        parent::__construct($message, $code);
    }
}
/**
 * Represents an HTTP 500 error.
 */
class InternalErrorException extends HttpException {
/**
 * Constructor
 *
 * @param string $message If no message is given 'Internal Server Error' will be the message
 * @param string $code Status code, defaults to 500
 */
    public function __construct($message = null, $code = 500) {
        if (empty($message)) {
            $message = 'Internal Server Error';
        }
        parent::__construct($message, $code);
    }
}
