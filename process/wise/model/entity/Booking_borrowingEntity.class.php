<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/11/3
 * Time: 16:55
 */

namespace wise;


class Booking_borrowingEntity extends \Entity {
    protected $booking_borrowing_id = null;
    protected $channel = 'Hotel';
    protected $company_id = 0;
    protected $channel_id;
    protected $booking_detail_id;
    protected $booking_number;
    protected $item_id;
    protected $item_name;
    protected $borrowing_id;
    protected $borrowing_name;
    protected $borrowing_num;
    protected $borrowing_return_num;
    protected $accounts_id;
    protected $cash_pledge;
    protected $payment_id;
    protected $payment_name;
    protected $employee_id;
    protected $employee_name;
    protected $valid;
    protected $add_datetime = '0000-00-00 00:00:00';

    /**
     * @return null
     */
    public function getBookingBorrowingId() {
        return $this->booking_borrowing_id;
    }

    /**
     * @param null $booking_borrowing_id
     */
    public function setBookingBorrowingId($booking_borrowing_id) {
        $this->booking_borrowing_id = $booking_borrowing_id;
    }

    /**
     * @return string
     */
    public function getChannel(): string {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel) {
        $this->channel = $channel;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int {
        return $this->company_id;
    }

    /**
     * @param int $company_id
     */
    public function setCompanyId(int $company_id) {
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
    public function getBookingDetailId() {
        return $this->booking_detail_id;
    }

    /**
     * @param mixed $booking_detail_id
     */
    public function setBookingDetailId($booking_detail_id) {
        $this->booking_detail_id = $booking_detail_id;
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
    public function getItemName() {
        return $this->item_name;
    }

    /**
     * @param mixed $item_name
     */
    public function setItemName($item_name) {
        $this->item_name = $item_name;
    }

    /**
     * @return mixed
     */
    public function getBorrowingId() {
        return $this->borrowing_id;
    }

    /**
     * @param mixed $borrowing_id
     */
    public function setBorrowingId($borrowing_id) {
        $this->borrowing_id = $borrowing_id;
    }

    /**
     * @return mixed
     */
    public function getBorrowingName() {
        return $this->borrowing_name;
    }

    /**
     * @param mixed $borrowing_name
     */
    public function setBorrowingName($borrowing_name) {
        $this->borrowing_name = $borrowing_name;
    }

    /**
     * @return mixed
     */
    public function getBorrowingNum() {
        return $this->borrowing_num;
    }

    /**
     * @param mixed $borrowing_num
     */
    public function setBorrowingNum($borrowing_num) {
        $this->borrowing_num = $borrowing_num;
    }

    /**
     * @return mixed
     */
    public function getBorrowingReturnNum() {
        return $this->borrowing_return_num;
    }

    /**
     * @param mixed $borrowing_return_num
     */
    public function setBorrowingReturnNum($borrowing_return_num) {
        $this->borrowing_return_num = $borrowing_return_num;
    }

    /**
     * @return mixed
     */
    public function getAccountsId() {
        return $this->accounts_id;
    }

    /**
     * @param mixed $accounts_id
     */
    public function setAccountsId($accounts_id) {
        $this->accounts_id = $accounts_id;
    }

    /**
     * @return mixed
     */
    public function getCashPledge() {
        return $this->cash_pledge;
    }

    /**
     * @param mixed $cash_pledge
     */
    public function setCashPledge($cash_pledge) {
        $this->cash_pledge = $cash_pledge;
    }

    /**
     * @return mixed
     */
    public function getPaymentId() {
        return $this->payment_id;
    }

    /**
     * @param mixed $payment_id
     */
    public function setPaymentId($payment_id): void {
        $this->payment_id = $payment_id;
    }

    /**
     * @return mixed
     */
    public function getPaymentName() {
        return $this->payment_name;
    }

    /**
     * @param mixed $payment_name
     */
    public function setPaymentName($payment_name): void {
        $this->payment_name = $payment_name;
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
    public function setEmployeeId($employee_id): void {
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
    public function setEmployeeName($employee_name): void {
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
     * @return string
     */
    public function getAddDatetime(): string {
        return $this->add_datetime;
    }

    /**
     * @param string $add_datetime
     */
    public function setAddDatetime(string $add_datetime) {
        $this->add_datetime = $add_datetime;
    }


}