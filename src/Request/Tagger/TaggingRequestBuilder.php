<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 15.05.2017
 * Time: 20:42
 */

namespace Szyku\NLTK\Request\Tagger;


use Szyku\NLTK\Assertion\Assertion;

class TaggingRequestBuilder
{
    /** @var array */
    private $sentences = [];

    public static function create()
    {
        return new self();
    }

    public function add($sentence)
    {
        Assertion::string($sentence, 'Sentence must a string.');
        Assertion::notEmpty($sentence, 'Sentence cannot be empty.');

        $this->sentences[] = $sentence;

        return $this;
    }

    public function addMany(array $sentences)
    {
        Assertion::allString($sentences, 'Provided sentences must be of string type.');
        Assertion::allNotEmpty($sentences, 'Cannot provide empty string as sentence.');

        foreach ($sentences as $sentence) {
            $this->sentences[] = $sentence;
        }

        return $this;
    }

    public function build()
    {
        Assertion::notNull($this->sentences, "You can build only once from this builder");
        $sentences = $this->sentences;

        return new TaggingRequest($sentences);
    }
}
