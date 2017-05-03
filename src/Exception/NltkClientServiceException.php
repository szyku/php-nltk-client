<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 21:32
 */

namespace Szyku\NLTK\Exception;


use Throwable;

class NltkClientServiceException extends \RuntimeException implements NltkClientExceptionInterface
{
    public function __construct($message = "", $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

}
