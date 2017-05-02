<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 19:38
 */

namespace Szyku\NLTK\Exception;

use Assert\InvalidArgumentException as BaseException;

class NltkClientAssertionException extends BaseException implements NltkClientExceptionInterface
{

}
