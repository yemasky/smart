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
	protected $market_father_id;
	protected $market_id;
	protected $market_name;
	protected $item_id;
	protected $item_name;
	protected $item_category_id;
	protected $item_category_name;
	protected $sales_id;
	protected $sales_name;
	protected $discount_type;
	protected $money;
	protected $accounts_type;
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
    public function getMarketFatherId() {
        return $this->market_father_id;
    }

    /**
     * @param mixed $market_father_id
     */
    public function setMarketFatherId($market_father_id) {
        $this->market_father_id = $market_father_id;
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
    public function getMarketName() {
        return $this->market_name;
    }

    /**
     * @param mixed $market_name
     */
    public function setMarketName($market_name) {
        $this->market_name = $market_name;
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
    public function getItemCategoryId() {
        return $this->item_category_id;
    }

    /**
     * @param mixed $item_category_id
     */
    public function setItemCategoryId($item_category_id) {
        $this->item_category_id = $item_category_id;
    }

    /**
     * @return mixed
     */
    public function getItemCategoryName() {
        return $this->item_category_name;
    }

    /**
     * @param mixed $item_category_name
     */
    public function setItemCategoryName($item_category_name) {
        $this->item_category_name = $item_category_name;
    }

    /**
     * @return mixed
     */
    public function getSalesId() {
        return $this->sales_id;
    }

    /**
     * @param mixed $sales_id
     */
    public function setSalesId($sales_id) {
        $this->sales_id = $sales_id;
    }

    /**
     * @return mixed
     */
    public function getSalesName() {
        return $this->sales_name;
    }

    /**
     * @param mixed $sales_name
     */
    public function setSalesName($sales_name) {
        $this->sales_name = $sales_name;
    }

    /**
     * @return mixed
     */
    public function getDiscountType() {
        return $this->discount_type;
    }

    /**
     * @param mixed $discount_type
     */
    public function setDiscountType($discount_type) {
        $this->discount_type = $discount_type;
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