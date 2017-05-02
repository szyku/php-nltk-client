<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 17:50
 */

namespace Szyku\NLTK\Response;


abstract class NltkResponse
{
    private $time;

    /**
     * @return mixed
     */
    public function lookupTime()
    {
        return $this->time;
    }


}
