<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 17:40
 */

namespace Szyku\NLTK\Response\Dictionary;

use Szyku\NLTK\Common\PartOfSpeech;

final class Definition
{
    /** @var string */
    private $definition;

    /** @var string */
    private $word;

    /** @var PartOfSpeech */
    private $partOfSpeech;

    /**
     * Definition constructor.
     * @param string $definition
     * @param string $phrase
     * @param string $partOfSpeech
     */
    public function __construct($definition, $phrase, PartOfSpeech $partOfSpeech)
    {
        $this->definition = $definition;
        $this->word = $phrase;
        $this->partOfSpeech = $partOfSpeech;
    }

    /**
     * @return string
     */
    public function definition()
    {
        return $this->definition;
    }

    /**
     * @return string
     */
    public function phrase()
    {
        return $this->word;
    }

    /**
     * @return PartOfSpeech
     */
    public function partOfSpeech()
    {
        return $this->partOfSpeech;
    }


}
