<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 16.05.2017
 * Time: 17:18
 */

namespace Szyku\NLTK\Response\Tagger;


use Szyku\NLTK\Assertion\Assertion;

final class TaggingResponse
{
    /** @var array */
    private $input = [];

    /** @var array */
    private $parts = [];

    /** @var float */
    private $time;

    /**
     * TaggingResponse constructor.
     * @param array $input
     * @param array $parts
     */
    public function __construct(array $input, array $parts, $time)
    {
        Assertion::allString($input, 'Input must be an array of strings.');
        foreach ($parts as $collection) {
            Assertion::allIsInstanceOf($collection, Part::class,
                sprintf('The parts array must contain only objects of class %s.', Part::class));
        }
        Assertion::float($time, 'Time must be float type');
        $this->input = $input;
        $this->parts = $parts;
        $this->time = $time;
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
