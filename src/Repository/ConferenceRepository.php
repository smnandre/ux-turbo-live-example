<?php

namespace App\Repository;

use App\Entity\Conference;
use DateTimeImmutable;
use DateTimeInterface;

class ConferenceRepository
{
    public function findAll(int $limit = 8): array
    {
        return array_slice($this->_fakeAll(), 0, $limit);
    }

    public function search(
        ?DateTimeInterface $date = null,
        ?string $city = null,
        string|array|null $category = null,
        int $limit = 10,
    ): array {
        $results = array_filter($this->_fakeAll(), fn (Conference $conference) => (
            // Filter by date (if set)
            (!$date || $conference->getDate()->format('Y-m-d') === $date->format('Y-m-d'))

            // Filter by category (if set)
            && (!$category || in_array($conference->getCategory(), (array) $category))

            // Filter by city (if set)
            && (!$city || str_contains(strtolower($conference->getCity()), $city))
        ));

        return array_slice($results, 0, $limit);
    }

    public function get(int $id): Conference
    {
        return $this->_fakeAll()[$id] ?? throw new \RuntimeException('Nope');
    }

    private function _fakeAll(): array
    {
        $results = [];
        $i = 0;
        foreach (['Paris', 'London', 'Madrid'] as $city) {
            foreach (['2025-01-01', '2025-01-02', '2025-01-03'] as $date) {
                $conference = new Conference();
                $conference->setId(++$i);
                $conference->setTitle('Symfony '.$i);
                $conference->setCountry(match ($city) {
                    'Paris' => 'France',
                    'London' => 'United Kingdom',
                    'Madrid' => 'Spain',
                    default => '',
                });
                $conference->setDescription(str_repeat(sprintf('The Symfony %d conference %s is gonna be outstanding! In %s and %s', $i, $city, $date, $city), 3));
                $conference->setCategory(['reunion', 'reunion', 'conference'][$i % 3]);
                $conference->setCity($city);
                $conference->setDate(new \DateTimeImmutable($date));
                $conference->setPrice(100 + $i);
                $results[$i] = $conference;
            }
        }

        return $results;
    }
}
