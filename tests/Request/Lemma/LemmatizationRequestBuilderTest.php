<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 21:01
 */

namespace Tests\Szyku\NLTK\Request\Lemma;

use Szyku\NLTK\Exception\NltkClientAssertionException;
use Szyku\NLTK\Request\Lemma\LemmaPosFilter;
use Szyku\NLTK\Request\Lemma\LemmatizationRequest;
use Szyku\NLTK\Request\Lemma\LemmatizationRequestBuilder;

class LemmatizationRequestBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var LemmatizationRequestBuilder */
    private $builder = null;

    protected function setUp()
    {
        $this->builder = LemmatizationRequestBuilder::create();
    }

    public function testBuildingFromScratch()
    {
        $request = $this->builder
            ->adjective('obese')
            ->noun('cat')
            ->verb('rolls')
            ->findAllFor('over')
            ->adverb('reluctantly')
            ->add("than", LemmaPosFilter::ADV())
            ->build();

        $this->assertInstanceOf(LemmatizationRequest::class, $request);
    }

    public function testThatBuildCannotHavePlaceIfNoItemsWereAdded()
    {
        $exception = null;
        try {
            $this->builder->build();
        } catch (NltkClientAssertionException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(NltkClientAssertionException::class, $exception);
        $this->assertSame("Cannot build request. No phrases has been added.", $exception->getMessage());
    }

    public function testThatEmptyStringCannotBeAdded()
    {
        $exception = null;
        try {
            $this->builder->adverb('');
        } catch (NltkClientAssertionException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(NltkClientAssertionException::class, $exception);
        $this->assertSame("Phrase cannot be empty.", $exception->getMessage());
    }

    public function testThatStringsCanBeAdded()
    {
        $exception = null;
        try {
            $this->builder->adverb(42);
        } catch (NltkClientAssertionException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(NltkClientAssertionException::class, $exception);
        $this->assertSame("Phrase must be string.", $exception->getMessage());
    }

    public function testThatOneBuilderCanBeUsedToBuildOnlyOnce()
    {
        $exception = null;
        try {
            $this->builder->adjective('quixotic')->build();
            $this->builder->build();
        } catch (NltkClientAssertionException $e) {
            $exception = $e;
        }

        $this->assertInstanceOf(NltkClientAssertionException::class, $exception);
        $this->assertContains(
            "Builder cannot build more than once using the same builder.",
            $exception->getMessage()
        );
    }
}
