<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 03.05.2017
 * Time: 19:24
 */

namespace Tests\Szyku\NLTK\Serialization;


trait File
{
    /**
     * @return string
     */
    private function forgeFilePath($fileName)
    {
        return __DIR__ . "/data/$fileName.json";
    }
}
