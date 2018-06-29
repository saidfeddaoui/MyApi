<?php
/**
 * Created by PhpStorm.
 * User: imac
 * Date: 25/06/2018
 * Time: 11:02
 */

namespace App\DTO\Api;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Serializer\ExclusionPolicy("none")
 */
class ApiResponse
{

    const OK = 200;
    const INTERNAL_SERVER_ERROR = 500;
    const STATUS_TEXT = [
        self::OK => 'OK',
        self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
    ];
    const HTTP_STATUS_CODE = [
        self::OK => Response::HTTP_OK,
        self::INTERNAL_SERVER_ERROR => Response::HTTP_INTERNAL_SERVER_ERROR,
    ];

    /**
     * @Serializer\Type("int")
     * @Serializer\Groups({"all"})
     *
     * @var int
     */
    private $code;
    /**
     * @Serializer\Type("string")
     * @Serializer\Groups({"all"})
     *
     * @var string
     */
    private $status;
    /**
     * Payload data to be send
     * @Serializer\Groups({"all"})
     * @var mixed
     */
    private $data;

    /**
     * ApiResponse constructor.
     * @param int $code
     * @param mixed $data
     */
    public function __construct($data = [], int $code = self::OK)
    {
        $this->data = $data;
        $this->status = self::STATUS_TEXT[$code] ?? null;
        $this->code = $code;
    }

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
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @param mixed $data
     * @return static
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return self::HTTP_STATUS_CODE[$this->code] ?? self::HTTP_STATUS_CODE[self::INTERNAL_SERVER_ERROR];
    }

}