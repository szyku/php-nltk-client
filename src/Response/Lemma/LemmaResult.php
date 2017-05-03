<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 18:36
 */

namespace Szyku\NLTK\Response\Lemma;


use Szyku\NLTK\Assertion\Assertion;

final class LemmaResult
{
    /** @var string */
    private $word;

    /** @var Lemma[] */
    private $lemmas = [];

    /**
     * LemmaResult constructor.
     * @param string $phrase
     * @param Lemma[] $lemma
     * @throws \InvalidArgumentException
     */
    public function __construct($phrase, array $lemmas)
    {
        Assertion::allIsInstanceOf($lemmas, Lemma::class, sprintf(
            "Passed array should contain only %s objects.", Lemma::class
        ));
        $this->word = $phrase;
        $this->lemmas = $lemmas;
    }

    /**
     * @return string
     */
    public function lemmatizedPhrase()
    {
        return $this->word;
    }

    /**
     * @return Lemma[]
     */
    public function lemmas()
    {
        return $this->lemmas;
    }


}
