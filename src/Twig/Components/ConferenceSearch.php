<?php

namespace App\Twig\Components;

use App\Repository\ConferenceRepository;
use DateTimeImmutable;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ConferenceSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true, format: 'Y-m-d')]
    public ?DateTimeImmutable $date = null;

    #[LiveProp(writable: true, url: true)]
    public ?string $city = null;

    #[LiveProp(writable: true)]
    public array $category = [];

    public function __construct(
        private readonly ConferenceRepository $conferenceRepository,
    ) {
    }

    public function getResults(): array
    {
        return $this->conferenceRepository->search(
            date: $this->date,
            city: $this->city,
            category: $this->category,
        );
    }
}
