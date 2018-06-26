<?php

namespace App\DTO\AladhanApi;

use JMS\Serializer\Annotation as Serializer;

class AladhanResponse
{

    const SUCCESS_CODE = 200;

    /**
     * @Serializer\Type("int")
     * @var int
     */
    private $code;
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $status;
    /**
     * @Serializer\Type("App\DTO\AladhanApi\Data")
     * @var Data
     */
    private $data;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }
    /**
     * @param int $code
     * @return static
     */
    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    /**
     * @param string $status
     * @return static
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
    /**
     * @return Data
     */
    public function getData(): Data
    {
        return $this->data;
    }
    /**
     * @param Data $data
     * @return static
     */
    public function setData(Data $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function isSuccess()
    {
        return self::SUCCESS_CODE === $this->code;
    }

}