<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2019/5/1
 * Time: 21:11
 */

namespace wise;


class Booking_evenEntity extends \Entity {
    protected $booking_even_id = null;
    protected $booking_even_type;
    protected $company_id;
    protected $channel_id;
    protected $item_id;
    protected $begin_datetime;
    protected $end_datetime;
    protected $even_node;
    protected $employee_id;
    protected $employee_name;
    protected $valid;
    protected $add_datetime;

    /**
     * @return null
     */
    public function getBookingEvenId() {
        return $this->booking_even_id;
    }

    /**
     * @param null $booking_even_id
     */
    public function setBookingEvenId($booking_even_id) {
        $this->booking_even_id = $booking_even_id;
    }

    /**
     * @return mixed
     */
    public function getBookingEvenType() {
        return $this->booking_even_type;
    }

    /**
     * @param mixed $booking_even_type
     */
    public function setBookingEvenType($booking_even_type) {
        $this->booking_even_type = $booking_even_type;
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
    public function getItemId() {
        return $this->item_id;
    }

    /**
     * @param mixed $item_id
     */
    public function setItemId($item_id) {
        $this->item_id = $item_id;
    }

    /**
     * @return mixed
     */
    public function getBeginDatetime() {
        return $this->begin_datetime;
    }

    /**
     * @param mixed $begin_datetime
     */
    public function setBeginDatetime($begin_datetime) {
        $this->begin_datetime = $begin_datetime;
    }

    /**
     * @return mixed
     */
    public function getEndDatetime() {
        return $this->end_datetime;
    }

    /**
     * @param mixed $end_datetime
     */
    public function setEndDatetime($end_datetime) {
        $this->end_datetime = $end_datetime;
    }

    /**
     * @return mixed
     */
    public function getEvenNode() {
        return $this->even_node;
    }

    /**
     * @param mixed $even_node
     */
    public function setEvenNode($even_node) {
        $this->even_node = $even_node;
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
    public function getValid() {
        return $this->valid;
    }

    /**
     * @param mixed $valid
     */
    public function setValid($valid) {
        $this->valid = $valid;
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