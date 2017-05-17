<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 17:21
 */

namespace Szyku\NLTK\Response\Tagger;


use Szyku\NLTK\Assertion\Assertion;

final class Part
{
    /** @var string */
    private $item;

    /** @var ExtendedPartOfSpeech */
    private $partOfSpeech;

    /**
     * Part constructor.
     * @param string $item
     * @param ExtendedPartOfSpeech $partOfSpeech
     */
    public function __construct($item, ExtendedPartOfSpeech $partOfSpeech)
    {
        Assertion::string($item, 'Item must be a string.');
        $this->item = $item;
        $this->partOfSpeech = $partOfSpeech;
    }

    /**
     * @return string
     */
    public function item()
    {
        return $this->item;
    }

    /**
     * @return ExtendedPartOfSpeech
     */
    public function partOfSpeech()
    {
        return $this->partOfSpeech;
    }


}
