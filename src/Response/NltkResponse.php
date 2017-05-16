<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 17:50
 */

namespace Szyku\NLTK\Response;


use Szyku\NLTK\Assertion\Assertion;

abstract class NltkResponse
{
    private $time;

    /**
     * NltkResponse constructor.
     * @param $time float
     */
    public function __construct($time)
    {
        Assertion::float($time, 'Time must be float type');
        $this->time = $time;
    }


    /**
     * @return mixed
     */
    public function lookupTime()
    {
        return $this->time;
    }


}
