<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/9/3
 * Time: 0:37
 */

namespace wise;

class Booking_accountsEntity extends \Entity {
	protected $accounts_id = null;
	protected $booking_detail_id;
	protected $booking_number;
	protected $booking_number_ext;
	protected $company_id;
	protected $channel;
	protected $booking_type;
	protected $channel_id;
	protected $member_id;
	protected $item_id;
	protected $item_name;
	protected $payment_id;
	protected $payment_name;
	protected $payment_father_id;
	protected $money;
	protected $credit_authorized_number;
	protected $credit_card_number;
	protected $credit_authorized_days;
	protected $credit_authorized_money;
	protected $accounts_type;
	protected $account_hanging_money;
	protected $accounts_status;
	protected $employee_id;
	protected $employee_name;
	protected $business_day;
	protected $valid;
	protected $add_datetime;

    /**
     * @return null
     */
    public function getAccountsId() {
        return $this->accounts_id;
    }

    /**
     * @param null $accounts_id
     */
    public function setAccountsId($accounts_id) {
        $this->accounts_id = $accounts_id;
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
    public function getBookingNumberExt() {
        return $this->booking_number_ext;
    }

    /**
     * @param mixed $booking_number_ext
     */
    public function setBookingNumberExt($booking_number_ext) {
        $this->booking_number_ext = $booking_number_ext;
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
    public function getChannel() {
        return $this->channel;
    }

    /**
     * @param mixed $channel
     */
    public function setChannel($channel) {
        $this->channel = $channel;
    }

    /**
     * @return mixed
     */
    public function getBookingType() {
        return $this->booking_type;
    }

    /**
     * @param mixed $booking_type
     */
    public function setBookingType($booking_type) {
        $this->booking_type = $booking_type;
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
    public function getMemberId() {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     */
    public function setMemberId($member_id) {
        $this->member_id = $member_id;
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
    public function getPaymentFatherId() {
        return $this->payment_father_id;
    }

    /**
     * @param mixed $payment_father_id
     */
    public function setPaymentFatherId($payment_father_id): void {
        $this->payment_father_id = $payment_father_id;
    }

    /**
     * @return mixed
     */
    public function getMoney() {
        return $this->money;
    }

    /**
     * @param mixed $money
     */
    public function setMoney($money) {
        $this->money = $money;
    }

    /**
     * @return mixed
     */
    public function getCreditAuthorizedNumber() {
        return $this->credit_authorized_number;
    }

    /**
     * @param mixed $credit_authorized_number
     */
    public function setCreditAuthorizedNumber($credit_authorized_number): void {
        $this->credit_authorized_number = $credit_authorized_number;
    }

    /**
     * @return mixed
     */
    public function getCreditCardNumber() {
        return $this->credit_card_number;
    }

    /**
     * @param mixed $credit_card_number
     */
    public function setCreditCardNumber($credit_card_number): void {
        $this->credit_card_number = $credit_card_number;
    }

    /**
     * @return mixed
     */
    public function getCreditAuthorizedDays() {
        return $this->credit_authorized_days;
    }

    /**
     * @param mixed $credit_authorized_days
     */
    public function setCreditAuthorizedDays($credit_authorized_days): void {
        $this->credit_authorized_days = $credit_authorized_days;
    }

    /**
     * @return mixed
     */
    public function getCreditAuthorizedMoney() {
        return $this->credit_authorized_money;
    }

    /**
     * @param mixed $credit_authorized_money
     */
    public function setCreditAuthorizedMoney($credit_authorized_money): void {
        $this->credit_authorized_money = $credit_authorized_money;
    }

    /**
     * @return mixed
     */
    public function getAccountsType() {
        return $this->accounts_type;
    }

    /**
     * @param mixed $accounts_type
     */
    public function setAccountsType($accounts_type) {
        $this->accounts_type = $accounts_type;
    }

    /**
     * @return mixed
     */
    public function getAccountHangingMoney() {
        return $this->account_hanging_money;
    }

    /**
     * @param mixed $account_hanging_money
     */
    public function setAccountHangingMoney($account_hanging_money): void {
        $this->account_hanging_money = $account_hanging_money;
    }

    /**
     * @return mixed
     */
    public function getAccountsStatus() {
        return $this->accounts_status;
    }

    /**
     * @param mixed $accounts_status
     */
    public function setAccountsStatus($accounts_status): void {
        $this->accounts_status = $accounts_status;
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