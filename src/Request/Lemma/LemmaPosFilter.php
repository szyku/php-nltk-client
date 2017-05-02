<?php
/**
 * Created by Szymon Szymański <szymon.szymanski@hotmail.com>
 * Date: 02.05.2017
 * Time: 18:28
 */

namespace Szyku\NLTK\Request\Lemma;


use MabeEnum\Enum;
use Szyku\NLTK\Common\PartOfSpeech;

final class LemmaPosFilter extends Enum
{
    const NOUN = PartOfSpeech::NOUN;
    const VERB = PartOfSpeech::VERB;
    const ADJ = PartOfSpeech::ADJ;
    const ADV = PartOfSpeech::ADV;
    const ALL = 'all';
}
