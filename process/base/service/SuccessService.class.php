<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/9/2
 * Time: 17:44
 */

class SuccessService {
    private $success = true;
    private $code = '000001';
    private $message = '';
    private $data = null;

    /**
     * @return bool
     */
    public function isSuccess(): bool {
        return $this->success;
    }

    /**
     * @param bool $success
     */
    public function setSuccess(bool $success) {
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getCode(): string {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code) {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message) {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    public function setSuccessService(bool $success, string $code, string $message, $data = null) : SuccessService {
        $this->success = $success;
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
        return $this;
    }
}