<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Response;

class Response
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    protected $errorMessage;

    public function __construct()
    {
    }

    public function hasError(): bool
    {
        return $this->errorMessage === '';
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @param array $data
     * @return Response
     */
    public function setData(array $data): Response
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $errorMessage
     * @return Response
     */
    public function setErrorMessage(string $errorMessage): Response
    {
        $this->errorMessage = $errorMessage;
        return $this;
    }
}