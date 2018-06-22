<?php

namespace App\DTO\AladhanApi;

use JMS\Serializer\Annotation as Serializer;

class Data
{

    /**
     * @Serializer\Type("array")
     * @var array
     */
    private $timings;

    /**
     * @return array
     */
    public function getTimings(): array
    {
        $removedData = ['Sunrise', 'Sunset', 'Imsak', 'Midnight'];
        return array_diff_key($this->timings, array_flip($removedData));
        return $this->timings;
    }
    /**
     * @param array $timings
     * @return static
     */
    public function setTimings(array $timings): self
    {
        $this->timings = $timings;
        return $this;
    }

    public function getUpcomingPrayerTime()
    {
        $now = new \DateTime();
        $prayer = [];
        foreach ($this->getTimings() as $key => $timing) {
            list($hour, $minutes) = explode(':', $timing);
            $time = (new \DateTime())->setTime($hour, $minutes);
            if ($time < $now) {
                continue;
            }
            $prayer = [$key => $timing];
            break;
        }
        return $prayer;
    }

}