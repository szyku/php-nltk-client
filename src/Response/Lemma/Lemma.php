<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 18:08
 */

namespace Szyku\NLTK\Response\Lemma;


use Szyku\NLTK\Common\PartOfSpeech;

final class Lemma
{
    /** @var string */
    private $lemma;

    /** @var PartOfSpeech */
    private $partOfSpeech;

    /**
     * Lemma constructor.
     * @param string $lemma
     * @param PartOfSpeech $partOfSpeech
     */
    public function __construct($lemma, PartOfSpeech $partOfSpeech)
    {
        $this->lemma = $lemma;
        $this->partOfSpeech = $partOfSpeech;
    }

    /**
     * @return string
     */
    public function lemma()
    {
        return $this->lemma;
    }

    /**
     * @return PartOfSpeech
     */
    public function partOfSpeech()
    {
        return $this->partOfSpeech;
    }


}
