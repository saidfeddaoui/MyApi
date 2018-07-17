<?php

namespace App\DTO\Api;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Serializer\ExclusionPolicy("none")
 */
class ApiResponse
{

    const OK = 200;
    const CREATED = 201;
    const UPDATED = 202;
    const UNAUTHORIZED = 403;
    const NOT_FOUND = 404;
    const CONSTRAINT_VIOLATION_ERROR = 406;
    const AUTHENTICATION_FAILURE = 407;
    const MISSING_REQUIREMENT_ERROR = 408;
    const INTERNAL_SERVER_ERROR = 500;
    const VERIFICATION_CODE_ERROR = 610;
    const CLIENT_NOT_VERIFIED_ERROR = 611;
    const CLIENT_ALREADY_VERIFIED_ERROR = 612;
    const CLIENT_ACCOUNT_ALREADY_CREATED_ERROR = 613;
    const JWT_FAILURE_ERROR = 730;
    const PRE_DECLARATION_CREATION_FAILED = 910;
    const DUPLICATED_PRE_DECLARATION = 911;
    const STATUS_TEXT = [
        self::OK => 'OK',
        self::CREATED => 'Created',
        self::UPDATED => 'Updated',
        self::UNAUTHORIZED => 'Unauthorized, An authentication is required',
        self::NOT_FOUND => 'Resource Not Found',
        self::CONSTRAINT_VIOLATION_ERROR => 'Constraint Violation Error',
        self::AUTHENTICATION_FAILURE => 'Authentication failed',
        self::MISSING_REQUIREMENT_ERROR => 'Missing requirement',
        self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
        self::VERIFICATION_CODE_ERROR => 'Verification code is not correct',
        self::CLIENT_NOT_VERIFIED_ERROR => 'Unauthorized action for an unverified client',
        self::CLIENT_ALREADY_VERIFIED_ERROR => 'Client is already verified',
        self::CLIENT_ACCOUNT_ALREADY_CREATED_ERROR => 'Client has already created his account',
        self::JWT_FAILURE_ERROR => 'Invalid Token',
        self::PRE_DECLARATION_CREATION_FAILED => 'Pre-declaration creation failed',
        self::DUPLICATED_PRE_DECLARATION => 'A pre-declaration with the same day and same contract already exist',
    ];
    const HTTP_STATUS_CODE = [
        self::OK => Response::HTTP_OK,
        self::CREATED => Response::HTTP_CREATED,
        self::UPDATED => Response::HTTP_ACCEPTED,
        self::UNAUTHORIZED => Response::HTTP_UNAUTHORIZED,
        self::NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::CONSTRAINT_VIOLATION_ERROR => Response::HTTP_NOT_ACCEPTABLE,
        self::AUTHENTICATION_FAILURE => Response::HTTP_BAD_REQUEST,
        self::MISSING_REQUIREMENT_ERROR => Response::HTTP_BAD_REQUEST,
        self::INTERNAL_SERVER_ERROR => Response::HTTP_INTERNAL_SERVER_ERROR,
        self::VERIFICATION_CODE_ERROR => Response::HTTP_BAD_REQUEST,
        self::CLIENT_NOT_VERIFIED_ERROR => Response::HTTP_UNAUTHORIZED,
        self::CLIENT_ALREADY_VERIFIED_ERROR => Response::HTTP_NOT_ACCEPTABLE,
        self::CLIENT_ACCOUNT_ALREADY_CREATED_ERROR => Response::HTTP_NOT_ACCEPTABLE,
        self::JWT_FAILURE_ERROR => Response::HTTP_UNAUTHORIZED,
        self::PRE_DECLARATION_CREATION_FAILED => Response::HTTP_PRECONDITION_FAILED,
        self::DUPLICATED_PRE_DECLARATION => Response::HTTP_NOT_ACCEPTABLE,
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