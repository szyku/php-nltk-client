<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 20:20
 */

namespace Szyku\NLTK\Request\Dictionary;


use Szyku\NLTK\Assertion\Assertion;

abstract class WordLookupRequest
{
    /** @var string */
    private $word;

    /** @var PosFilter */
    private $partOfSpeech = null;

    /**
     * WordLookupRequest constructor.
     * @param string $phrase
     * @param PosFilter $partOfSpeech
     */
    private function __construct($phrase, PosFilter $partOfSpeech = null)
    {
        Assertion::string($phrase, "Phrase must be string.");
        Assertion::notEmpty($phrase, "Phrase cannot be empty.");

        $this->word = $phrase;
        $this->partOfSpeech = $partOfSpeech;
    }

    public static function phrase($phrase, PosFilter $filter = null)
    {
        return new static($phrase, $filter);
    }

    public static function noun($phrase)
    {
        return new static($phrase, PosFilter::NOUN());
    }

    public static function verb($phrase)
    {
        return new static($phrase, PosFilter::VERB());
    }

    public static function adjective($phrase)
    {
        return new static($phrase, PosFilter::ADJ());
    }

    public static function adverb($phrase)
    {
        return new static($phrase, PosFilter::ADV());
    }

    /**
     * @return string
     */
    public function word()
    {
        return $this->word;
    }

    /**
     * @return bool
     */
    public function isNarrowedByPos()
    {
        return null !== $this->partOfSpeech;
    }

    /**
     * @return PosFilter
     */
    public function partOfSpeech()
    {
        return $this->partOfSpeech;
    }

}
