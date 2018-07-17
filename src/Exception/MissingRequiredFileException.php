<?php

namespace App\Exception;

class MissingRequiredFileException extends \Exception
{

    /**
     * @var string
     */
    private $name;

    /**
     * MissingRequiredFileException constructor.
     * @param string $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $name;
        if ($name) {
            parent::__construct('Missing required file: ' . $this->getName());
        }
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

}