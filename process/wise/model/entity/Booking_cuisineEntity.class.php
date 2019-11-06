<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2019/5/1
 * Time: 21:11
 */

namespace wise;


class Booking_cuisineEntity extends \Entity {
    protected $booking_cuisine_id = null;
    protected $booking_detail_id;
    protected $booking_number;
    protected $company_id;
    protected $channel_id;
    protected $cuisine_category_id;
    protected $cuisine_id;
    protected $cuisine_name;
    protected $cuisine_number;
    protected $cuisine_number_over;
    protected $cuisine_number_return;
    protected $item_id;
    protected $item_name;
    protected $member_id;
    protected $member_name;
    protected $add_datetime;

    /**
     * @return null
     */
    public function getBookingCuisineId() {
        return $this->booking_cuisine_id;
    }

    /**
     * @param null $booking_cuisine_id
     */
    public function setBookingCuisineId($booking_cuisine_id) {
        $this->booking_cuisine_id = $booking_cuisine_id;
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
    public function getCuisineCategoryId() {
        return $this->cuisine_category_id;
    }

    /**
     * @param mixed $cuisine_category_id
     */
    public function setCuisineCategoryId($cuisine_category_id) {
        $this->cuisine_category_id = $cuisine_category_id;
    }

    /**
     * @return mixed
     */
    public function getCuisineId() {
        return $this->cuisine_id;
    }

    /**
     * @param mixed $cuisine_id
     */
    public function setCuisineId($cuisine_id) {
        $this->cuisine_id = $cuisine_id;
    }

    /**
     * @return mixed
     */
    public function getCuisineName() {
        return $this->cuisine_name;
    }

    /**
     * @param mixed $cuisine_name
     */
    public function setCuisineName($cuisine_name) {
        $this->cuisine_name = $cuisine_name;
    }

    /**
     * @return mixed
     */
    public function getCuisineNumber() {
        return $this->cuisine_number;
    }

    /**
     * @param mixed $cuisine_number
     */
    public function setCuisineNumber($cuisine_number) {
        $this->cuisine_number = $cuisine_number;
    }

    /**
     * @return mixed
     */
    public function getCuisineNumberOver() {
        return $this->cuisine_number_over;
    }

    /**
     * @param mixed $cuisine_number_over
     */
    public function setCuisineNumberOver($cuisine_number_over) {
        $this->cuisine_number_over = $cuisine_number_over;
    }

    /**
     * @return mixed
     */
    public function getCuisineNumberReturn() {
        return $this->cuisine_number_return;
    }

    /**
     * @param mixed $cuisine_number_return
     */
    public function setCuisineNumberReturn($cuisine_number_return) {
        $this->cuisine_number_return = $cuisine_number_return;
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