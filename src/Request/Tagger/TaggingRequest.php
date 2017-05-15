<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 20:39
 */

namespace Szyku\NLTK\Request\Tagger;


use Szyku\NLTK\Assertion\Assertion;

final class TaggingRequest
{
    /** @var array */
    private $sentences = [];

    /**
     * TaggingRequest constructor.
     * @param array $sentences
     */
    public function __construct(array $sentences)
    {
        Assertion::allString($sentences);
        $this->sentences = $sentences;
    }

    /**
     * @return array
     */
    public function sentences()
    {
        return $this->sentences;
    }

}
