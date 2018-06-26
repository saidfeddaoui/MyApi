<?php

namespace App\DTO\Api\ContentType;

use JMS\Serializer\Annotation as Serializer;

class Prayer
{

    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $name;
    /**
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @var \DateTime
     */
    private $time;

    /**
     * Prayer constructor.
     * @param string $name
     * @param \DateTime $time
     */
    public function __construct(string $name = null, \DateTime $time = null)
    {
        $this->name = $name;
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @param string $name
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }
    /**
     * @param \DateTime $time
     * @return static
     */
    public function setTime(\DateTime $time): self
    {
        $this->time = $time;
        return $this;
    }

}