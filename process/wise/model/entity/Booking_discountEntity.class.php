<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2019/5/1
 * Time: 21:11
 */

namespace wise;


class Booking_discountEntity extends \Entity {
    protected $booking_discount_id = null;
    protected $company_id;
    protected $channel_id;
    protected $booking_number;
    protected $booking_detail_id;
    protected $item_id;
    protected $item_extend_id;
    protected $consume_id;
    protected $discount_id;
    protected $discount_category;
    protected $discount_type;
    protected $discount;
    protected $coupon_code;
    protected $coupon_code_password;
    protected $arefavorable_money;
    protected $business_day;
    protected $member_id;
    protected $member_name;
    protected $valid;
    protected $add_datetime;

    /**
     * @return null
     */
    public function getBookingDiscountId() {
        return $this->booking_discount_id;
    }

    /**
     * @param null $booking_discount_id
     */
    public function setBookingDiscountId($booking_discount_id) {
        $this->booking_discount_id = $booking_discount_id;
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
    public function getItemExtendId() {
        return $this->item_extend_id;
    }

    /**
     * @param mixed $item_extend_id
     */
    public function setItemExtendId($item_extend_id) {
        $this->item_extend_id = $item_extend_id;
    }

    /**
     * @return mixed
     */
    public function getConsumeId() {
        return $this->consume_id;
    }

    /**
     * @param mixed $consume_id
     */
    public function setConsumeId($consume_id) {
        $this->consume_id = $consume_id;
    }

    /**
     * @return mixed
     */
    public function getDiscountId() {
        return $this->discount_id;
    }

    /**
     * @param mixed $discount_id
     */
    public function setDiscountId($discount_id) {
        $this->discount_id = $discount_id;
    }

    /**
     * @return mixed
     */
    public function getDiscountCategory() {
        return $this->discount_category;
    }

    /**
     * @param mixed $discount_category
     */
    public function setDiscountCategory($discount_category) {
        $this->discount_category = $discount_category;
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
    public function getDiscount() {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    /**
     * @return mixed
     */
    public function getCouponCode() {
        return $this->coupon_code;
    }

    /**
     * @param mixed $coupon_code
     */
    public function setCouponCode($coupon_code) {
        $this->coupon_code = $coupon_code;
    }

    /**
     * @return mixed
     */
    public function getCouponCodePassword() {
        return $this->coupon_code_password;
    }

    /**
     * @param mixed $coupon_code_password
     */
    public function setCouponCodePassword($coupon_code_password) {
        $this->coupon_code_password = $coupon_code_password;
    }

    /**
     * @return mixed
     */
    public function getArefavorableMoney() {
        return $this->arefavorable_money;
    }

    /**
     * @param mixed $arefavorable_money
     */
    public function setArefavorableMoney($arefavorable_money) {
        $this->arefavorable_money = $arefavorable_money;
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
    public function getMemberName() {
        return $this->member_name;
    }

    /**
     * @param mixed $member_name
     */
    public function setMemberName($member_name) {
        $this->member_name = $member_name;
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