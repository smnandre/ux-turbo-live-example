<?php

namespace App\Twig\Components;

use App\Entity\Conference;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class ConferenceCard
{
    public Conference $conference;
}
