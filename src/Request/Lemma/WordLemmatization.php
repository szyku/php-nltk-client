<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 18:47
 */

namespace Szyku\NLTK\Request\Lemma;

use Szyku\NLTK\Assertion\Assertion;
use Szyku\NLTK\Exception\NltkClientAssertionException;

final class WordLemmatization
{
    /** @var string */
    private $word;

    /** @var LemmaPosFilter */
    private $partOfSpeech;

    /**
     * WordLemmatization constructor.
     * @param string $phrase
     * @param LemmaPosFilter $partOfSpeech
     * @throws NltkClientAssertionException
     */
    public function __construct($phrase, LemmaPosFilter $partOfSpeech)
    {
        Assertion::string($phrase, "Phrase must be string.");
        Assertion::notEmpty($phrase, "Phrase cannot be empty.");

        $this->word = $phrase;
        $this->partOfSpeech = $partOfSpeech;
    }

    /**
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @return LemmaPosFilter
     */
    public function getPartOfSpeech()
    {
        return $this->partOfSpeech;
    }


}
