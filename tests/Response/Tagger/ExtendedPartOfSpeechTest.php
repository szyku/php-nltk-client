<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 20:56
 */

namespace Tests\Szyku\NLTK\Response\Tagger;

use Szyku\NLTK\Response\Tagger\ExtendedPartOfSpeech;

class ExtendedPartOfSpeechTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider expectedResultsProvider
     */
    public function testCreation($symbol, ExtendedPartOfSpeech $expected, $expectedSymbol, $expectedDescription)
    {
        $pos = ExtendedPartOfSpeech::createFromKey($symbol);
        $this->assertInstanceOf(ExtendedPartOfSpeech::class, $pos);
        $this->assertTrue($pos->is($expected));
        $this->assertSame($pos->humanizedToken(), $expectedDescription);
        $this->assertSame($pos->token(), $expectedSymbol);
    }

    public function expectedResultsProvider()
    {
        return [
            ['dollar', ExtendedPartOfSpeech::DOLLAR(), '$', 'dollar'],
            ['``', ExtendedPartOfSpeech::QUOTE_OPEN(), '``', 'quote_open'],
            ['foreign word', ExtendedPartOfSpeech::FOREIGN_WORD(), 'FW', 'foreign word'],
            ['SYM', ExtendedPartOfSpeech::SYMBOL(), 'SYM', 'symbol'],
            ['list_item_marker', ExtendedPartOfSpeech::LIST_MRK(), 'LS', 'list_item_marker'],
        ];
    }
}
