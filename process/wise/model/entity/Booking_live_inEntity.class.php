<?php


namespace wise;


class Booking_live_inEntity extends \Entity {
    protected $live_in_id = null;
    protected $company_id;
    protected $channel_id;
    protected $booking_detail_id;
    protected $booking_number;
    protected $item_id;
    protected $item_name;
    protected $member_id;
    protected $member_name;
    protected $member_mobile;
    protected $member_email;
    protected $member_sex;
    protected $member_idcard_type;
    protected $member_idcard_number;
    protected $live_in_datetime;
    protected $live_out_datetime;
    protected $employee_id;
    protected $employee_name;
    protected $valid;
    protected $add_datetime;

    /**
     * @return null
     */
    public function getLiveInId() {
        return $this->live_in_id;
    }

    /**
     * @param null $live_in_id
     */
    public function setLiveInId($live_in_id): void {
        $this->live_in_id = $live_in_id;
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
    public function setCompanyId($company_id): void {
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
    public function setChannelId($channel_id): void {
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
    public function setBookingDetailId($booking_detail_id): void {
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
    public function setBookingNumber($booking_number): void {
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
    public function setItemId($item_id): void {
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
    public function setItemName($item_name): void {
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
    public function setMemberId($member_id): void {
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
    public function setMemberName($member_name): void {
        $this->member_name = $member_name;
    }

    /**
     * @return mixed
     */
    public function getMemberMobile() {
        return $this->member_mobile;
    }

    /**
     * @param mixed $member_mobile
     */
    public function setMemberMobile($member_mobile): void {
        $this->member_mobile = $member_mobile;
    }

    /**
     * @return mixed
     */
    public function getMemberEmail() {
        return $this->member_email;
    }

    /**
     * @param mixed $member_email
     */
    public function setMemberEmail($member_email): void {
        $this->member_email = $member_email;
    }

    /**
     * @return mixed
     */
    public function getMemberSex() {
        return $this->member_sex;
    }

    /**
     * @param mixed $member_sex
     */
    public function setMemberSex($member_sex): void {
        $this->member_sex = $member_sex;
    }

    /**
     * @return mixed
     */
    public function getMemberIdcardType() {
        return $this->member_idcard_type;
    }

    /**
     * @param mixed $member_idcard_type
     */
    public function setMemberIdcardType($member_idcard_type): void {
        $this->member_idcard_type = $member_idcard_type;
    }

    /**
     * @return mixed
     */
    public function getMemberIdcardNumber() {
        return $this->member_idcard_number;
    }

    /**
     * @param mixed $member_idcard_number
     */
    public function setMemberIdcardNumber($member_idcard_number): void {
        $this->member_idcard_number = $member_idcard_number;
    }

    /**
     * @return mixed
     */
    public function getLiveInDatetime() {
        return $this->live_in_datetime;
    }

    /**
     * @param mixed $live_in_datetime
     */
    public function setLiveInDatetime($live_in_datetime): void {
        $this->live_in_datetime = $live_in_datetime;
    }

    /**
     * @return mixed
     */
    public function getLiveOutDatetime() {
        return $this->live_out_datetime;
    }

    /**
     * @param mixed $live_out_datetime
     */
    public function setLiveOutDatetime($live_out_datetime): void {
        $this->live_out_datetime = $live_out_datetime;
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
    public function setValid($valid): void {
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
    public function setAddDatetime($add_datetime): void {
        $this->add_datetime = $add_datetime;
    }



}