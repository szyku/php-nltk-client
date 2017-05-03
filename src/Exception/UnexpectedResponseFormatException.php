<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 15:26
 */

namespace Szyku\NLTK\Exception;


class UnexpectedResponseFormatException extends \RuntimeException implements NltkClientExceptionInterface
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }
}
