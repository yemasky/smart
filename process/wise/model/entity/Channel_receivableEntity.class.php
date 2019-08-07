<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2019/8/6
 * Time: 20:33
 */

namespace wise;


class Channel_receivableEntity extends \Entity {
    protected $receivable_id = null;
    protected $company_id;
    protected $channel_id;
    protected $receivable_name;
    protected $receivable_type;
    protected $receivable_address;
    protected $receivable_credit_code;
    protected $market_id;
    protected $member_id;
    protected $contact_name;
    protected $contact_mobile;
    protected $contact_email;
    protected $bank;
    protected $bank_account;
    protected $credit;
    protected $credit_used;
    protected $the_cumulative;
    protected $the_cumulative_payback;
    protected $valid_date;
    protected $employee_id;
    protected $employee_name;
    protected $add_datetime;
    protected $valid;

    /**
     * @return null
     */
    public function getReceivableId() {
        return $this->receivable_id;
    }

    /**
     * @param null $receivable_id
     */
    public function setReceivableId($receivable_id) {
        $this->receivable_id = $receivable_id;
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
    public function getReceivableName() {
        return $this->receivable_name;
    }

    /**
     * @param mixed $receivable_name
     */
    public function setReceivableName($receivable_name) {
        $this->receivable_name = $receivable_name;
    }

    /**
     * @return mixed
     */
    public function getReceivableType() {
        return $this->receivable_type;
    }

    /**
     * @param mixed $receivable_type
     */
    public function setReceivableType($receivable_type) {
        $this->receivable_type = $receivable_type;
    }

    /**
     * @return mixed
     */
    public function getReceivableAddress() {
        return $this->receivable_address;
    }

    /**
     * @param mixed $receivable_address
     */
    public function setReceivableAddress($receivable_address) {
        $this->receivable_address = $receivable_address;
    }

    /**
     * @return mixed
     */
    public function getReceivableCreditCode() {
        return $this->receivable_credit_code;
    }

    /**
     * @param mixed $receivable_credit_code
     */
    public function setReceivableCreditCode($receivable_credit_code) {
        $this->receivable_credit_code = $receivable_credit_code;
    }

    /**
     * @return mixed
     */
    public function getMarketId() {
        return $this->market_id;
    }

    /**
     * @param mixed $market_id
     */
    public function setMarketId($market_id) {
        $this->market_id = $market_id;
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
    public function getContactName() {
        return $this->contact_name;
    }

    /**
     * @param mixed $contact_name
     */
    public function setContactName($contact_name) {
        $this->contact_name = $contact_name;
    }

    /**
     * @return mixed
     */
    public function getContactMobile() {
        return $this->contact_mobile;
    }

    /**
     * @param mixed $contact_mobile
     */
    public function setContactMobile($contact_mobile) {
        $this->contact_mobile = $contact_mobile;
    }

    /**
     * @return mixed
     */
    public function getContactEmail() {
        return $this->contact_email;
    }

    /**
     * @param mixed $contact_email
     */
    public function setContactEmail($contact_email) {
        $this->contact_email = $contact_email;
    }

    /**
     * @return mixed
     */
    public function getBank() {
        return $this->bank;
    }

    /**
     * @param mixed $bank
     */
    public function setBank($bank) {
        $this->bank = $bank;
    }

    /**
     * @return mixed
     */
    public function getBankAccount() {
        return $this->bank_account;
    }

    /**
     * @param mixed $bank_account
     */
    public function setBankAccount($bank_account) {
        $this->bank_account = $bank_account;
    }

    /**
     * @return mixed
     */
    public function getCredit() {
        return $this->credit;
    }

    /**
     * @param mixed $credit
     */
    public function setCredit($credit) {
        $this->credit = $credit;
    }

    /**
     * @return mixed
     */
    public function getCreditUsed() {
        return $this->credit_used;
    }

    /**
     * @param mixed $credit_used
     */
    public function setCreditUsed($credit_used) {
        $this->credit_used = $credit_used;
    }

    /**
     * @return mixed
     */
    public function getTheCumulative() {
        return $this->the_cumulative;
    }

    /**
     * @param mixed $the_cumulative
     */
    public function setTheCumulative($the_cumulative) {
        $this->the_cumulative = $the_cumulative;
    }

    /**
     * @return mixed
     */
    public function getTheCumulativePayback() {
        return $this->the_cumulative_payback;
    }

    /**
     * @param mixed $the_cumulative_payback
     */
    public function setTheCumulativePayback($the_cumulative_payback) {
        $this->the_cumulative_payback = $the_cumulative_payback;
    }

    /**
     * @return mixed
     */
    public function getValidDate() {
        return $this->valid_date;
    }

    /**
     * @param mixed $valid_date
     */
    public function setValidDate($valid_date) {
        $this->valid_date = $valid_date;
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
    
    
}