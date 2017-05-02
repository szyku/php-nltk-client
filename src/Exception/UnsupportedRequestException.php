<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 23:02
 */

namespace Szyku\NLTK\Exception;


class UnsupportedRequestException extends \InvalidArgumentException implements NltkClientExceptionInterface
{
    public function __construct($message = "")
    {
        parent::__construct($message);
    }

}
