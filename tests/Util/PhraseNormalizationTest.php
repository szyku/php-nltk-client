<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 23:22
 */

namespace Tests\Szyku\NLTK\Util;

use Szyku\NLTK\Util\PhraseNormalization;

class PhraseNormalizationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider stringProvider
     */
    public function testStringNormalization($in, $expected)
    {
        $actual = PhraseNormalization::normalizeForApi($in);
        $this->assertSame($expected, $actual, 'The normalization is incorrect.');
    }


    public function stringProvider()
    {
        return [
            ['look', 'look',],
            ['lock in', 'lock_in',],
            ['    space    ', 'space',],
            ['cat-o\'-nine-tails', 'cat-o\'-nine-tails',],
        ];
    }

    /**
     * @expectedException Szyku\NLTK\Exception\NltkClientAssertionException
     */
    public function testIfReactsWithExceptionOnIncorrectDataToNormalize()
    {
        PhraseNormalization::normalizeForApi(1233);
    }
}
