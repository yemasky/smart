<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/11/3
 * Time: 17:00
 */

namespace wise;


class Booking_operationEntity extends \Entity {
    protected $operation_id = null;
    protected $booking_number;
    protected $company_id;
    protected $module_id;
    protected $module_name;
    protected $method;
    protected $channel_id;
    protected $request;
    protected $operation_title;
    protected $operation_content;
    protected $business_day;
    protected $employee_id;
    protected $employee_name;
    protected $add_datetime;

    /**
     * @return null
     */
    public function getOperationId() {
        return $this->operation_id;
    }

    /**
     * @param null $operation_id
     */
    public function setOperationId($operation_id) {
        $this->operation_id = $operation_id;
    }

    /**
     * @return mixed
     */
    public function getBookingNumber() {
        return $this->booking_number;
    }

    /**
     * @param mixed $booking_number
     */
    public function setBookingNumber($booking_number) {
        $this->booking_number = $booking_number;
    }

    /**
     * @return mixed
     */
    public function getCompanyId() {
        return $this->company_id;
    }

    /**
     * @param mixed $company_id
     */
    public function setCompanyId($company_id) {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getModuleId() {
        return $this->module_id;
    }

    /**
     * @param mixed $module_id
     */
    public function setModuleId($module_id) {
        $this->module_id = $module_id;
    }

    /**
     * @return mixed
     */
    public function getModuleName() {
        return $this->module_name;
    }

    /**
     * @param mixed $module_name
     */
    public function setModuleName($module_name) {
        $this->module_name = $module_name;
    }

    /**
     * @return mixed
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method) {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getChannelId() {
        return $this->channel_id;
    }

    /**
     * @param mixed $channel_id
     */
    public function setChannelId($channel_id) {
        $this->channel_id = $channel_id;
    }

    /**
     * @return mixed
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getOperationTitle() {
        return $this->operation_title;
    }

    /**
     * @param mixed $operation_title
     */
    public function setOperationTitle($operation_title) {
        $this->operation_title = $operation_title;
    }

    /**
     * @return mixed
     */
    public function getOperationContent() {
        return $this->operation_content;
    }

    /**
     * @param mixed $operation_content
     */
    public function setOperationContent($operation_content) {
        $this->operation_content = $operation_content;
    }

    /**
     * @return mixed
     */
    public function getBusinessDay() {
        return $this->business_day;
    }

    /**
     * @param mixed $business_day
     */
    public function setBusinessDay($business_day) {
        $this->business_day = $business_day;
    }

    /**
     * @return mixed
     */
    public function getEmployeeId() {
        return $this->employee_id;
    }

    /**
     * @param mixed $employee_id
     */
    public function setEmployeeId($employee_id) {
        $this->employee_id = $employee_id;
    }

    /**
     * @return mixed
     */
    public function getEmployeeName() {
        return $this->employee_name;
    }

    /**
     * @param mixed $employee_name
     */
    public function setEmployeeName($employee_name) {
        $this->employee_name = $employee_name;
    }

    /**
     * @return mixed
     */
    public function getAddDatetime() {
        return $this->add_datetime;
    }

    /**
     * @param mixed $add_datetime
     */
    public function setAddDatetime($add_datetime) {
        $this->add_datetime = $add_datetime;
    }

}