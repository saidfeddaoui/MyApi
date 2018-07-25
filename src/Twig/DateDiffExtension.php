<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateDiffExtension extends AbstractExtension
{

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_diff', [$this, 'dateDiff']),
        ];
    }
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('date_diff', [$this, 'dateDiff']),
        ];
    }

    public function dateDiff(\DateTimeInterface $date, \DateTimeInterface $date2 = null)
    {
        if (!$date2) {
            $date2 = new \DateTime();
        }
        $interval = $date->diff($date2);
        if ($interval->d) {
            return "{$interval->d} Jours";
        }
        if ($interval->h) {
            return "{$interval->h} Heures";
        }
        if ($interval->i) {
            return "{$interval->h} Minutes";
        }
        return 'Maintenant';
    }

}
