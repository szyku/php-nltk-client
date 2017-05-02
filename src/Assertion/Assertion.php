<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 19:36
 */

namespace Szyku\NLTK\Assertion;

use Assert\Assertion as BaseAssertion;
use Szyku\NLTK\Exception\NltkClientAssertionException;

class Assertion extends BaseAssertion
{
    protected static $exceptionClass = NltkClientAssertionException::class;
}
