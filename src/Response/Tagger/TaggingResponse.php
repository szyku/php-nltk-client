<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 17:18
 */

namespace Szyku\NLTK\Response\Tagger;


use Szyku\NLTK\Assertion\Assertion;
use Szyku\NLTK\Response\NltkResponse;

final class TaggingResponse extends NltkResponse
{
    /** @var array */
    private $input = [];

    /** @var array */
    private $parts = [];

    /**
     * TaggingResponse constructor.
     * @param array $input
     * @param array $parts
     */
    public function __construct(array $input, array $parts, $time)
    {
        parent::__construct($time);
        Assertion::allString($input, 'Input must be an array of strings.');
        foreach ($parts as $collection) {
            Assertion::allIsInstanceOf($collection, Part::class,
                sprintf('The parts array must contain only objects of class %s.', Part::class));
        }
        $this->input = $input;
        $this->parts = $parts;
    }

    /**
     * @return array
     */
    public function input()
    {
        return $this->input;
    }

    /**
     * @return array
     */
    public function parts()
    {
        return $this->parts;
    }


}
