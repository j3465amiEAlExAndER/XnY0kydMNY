<?php
// 代码生成时间: 2025-09-22 19:45:22
class ApiResponseFormatter
{
    private $statusCode;
    private $message;
    private $data;
    private $errors;

    public function __construct(
        int $statusCode = 200,
        string $message = "",
        array $data = [],
        array $errors = []
    ) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
    }

    /**
     * Set the status code of the response
     *
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Set the message of the response
     *
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the data of the response
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set the errors of the response
     *
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Format the response into a JSON string
     *
     * @return string
     */
    public function format(): string
    {
        $response = [
            "status_code" => $this->statusCode,
            "message" => $this->message,
            "data" => $this->data,
            