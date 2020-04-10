<?php


namespace App\Utils\Enums\Recommandation;

abstract class RecommandationState
{
    const IGNORED = 'IGNORED';
    const TO_SEE = 'TO_SEE';
    const SEEN = 'SEEN';
    const UNSET = '';
}