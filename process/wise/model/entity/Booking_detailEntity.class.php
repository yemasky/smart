<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/9/1
 * Time: 23:56
 */

namespace wise;

class Booking_detailEntity extends \Entity {
	protected $booking_detail_id = null;
	protected $booking_number;
	protected $booking_number_ext = '';
	protected $company_id;
	protected $channel;
	protected $booking_type = 'room_day';
	protected $channel_id;
	protected $member_id = 0;
	protected $market_father_id = 0;
	protected $market_id = 0;
	protected $market_name = '';
	protected $item_id = 0;
	protected $item_name = '';
	protected $item_category_id = 0;
	protected $item_category_name = '';
	protected $check_in;
	protected $check_out;
	protected $actual_chenk_in;
	protected $actual_check_out;
	protected $booking_detail_status = '0';
	protected $employee_id = 0;
	protected $employee_name = '';
	protected $business_day;
	protected $sales_id = 0;
	protected $sales_name = '';
	protected $discount_type;
	protected $price_system_id = 0;
	protected $price_system_name = '';
	protected $source_price = 0.00;
	protected $total_price = 0.00;
	protected $client = 'pms';
	protected $valid = '1';
	protected $add_datetime;

	public function __construct(array $arrayInput = []) {
		if(empty($arrayInput)) return $this->setPrototype($arrayInput);
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
     * @return string
     */
    public function getBookingNumberExt(): string {
        return $this->booking_number_ext;
    }

    /**
     * @param string $booking_number_ext
     */
    public function setBookingNumberExt(string $booking_number_ext) {
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
	 * @return string
	 */
	public function getBookingType(): string {
		return $this->booking_type;
	}

	/**
	 * @param string $booking_type
	 */
	public function setBookingType(string $booking_type) {
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
	 * @return int
	 */
	public function getMemberId(): int {
		return $this->member_id;
	}

	/**
	 * @param int $member_id
	 */
	public function setMemberId(int $member_id) {
		$this->member_id = $member_id;
	}

	/**
	 * @return int
	 */
	public function getMarketFatherId(): int {
		return $this->market_father_id;
	}

	/**
	 * @param int $market_father_id
	 */
	public function setMarketFatherId(int $market_father_id) {
		$this->market_father_id = $market_father_id;
	}

	/**
	 * @return int
	 */
	public function getMarketId(): int {
		return $this->market_id;
	}

	/**
	 * @param int $market_id
	 */
	public function setMarketId(int $market_id) {
		$this->market_id = $market_id;
	}

	/**
	 * @return string
	 */
	public function getMarketName(): string {
		return $this->market_name;
	}

	/**
	 * @param string $market_name
	 */
	public function setMarketName(string $market_name) {
		$this->market_name = $market_name;
	}

	/**
	 * @return int
	 */
	public function getItemId(): int {
		return $this->item_id;
	}

	/**
	 * @param int $item_id
	 */
	public function setItemId(int $item_id) {
		$this->item_id = $item_id;
	}

	/**
	 * @return string
	 */
	public function getItemName(): string {
		return $this->item_name;
	}

	/**
	 * @param string $item_name
	 */
	public function setItemName(string $item_name) {
		$this->item_name = $item_name;
	}

	/**
	 * @return int
	 */
	public function getItemCategoryId(): int {
		return $this->item_category_id;
	}

	/**
	 * @param int $item_category_id
	 */
	public function setItemCategoryId(int $item_category_id) {
		$this->item_category_id = $item_category_id;
	}

	/**
	 * @return string
	 */
	public function getItemCategoryName(): string {
		return $this->item_category_name;
	}

	/**
	 * @param string $item_category_name
	 */
	public function setItemCategoryName(string $item_category_name) {
		$this->item_category_name = $item_category_name;
	}

	/**
	 * @return mixed
	 */
	public function getCheckIn() {
		return $this->check_in;
	}

	/**
	 * @param mixed $check_in
	 */
	public function setCheckIn($check_in) {
		$this->check_in = $check_in;
	}

	/**
	 * @return mixed
	 */
	public function getCheckOut() {
		return $this->check_out;
	}

	/**
	 * @param mixed $check_out
	 */
	public function setCheckOut($check_out) {
		$this->check_out = $check_out;
	}

	/**
	 * @return mixed
	 */
	public function getActualChenkIn() {
		return $this->actual_chenk_in;
	}

	/**
	 * @param mixed $actual_chenk_in
	 */
	public function setActualChenkIn($actual_chenk_in) {
		$this->actual_chenk_in = $actual_chenk_in;
	}

	/**
	 * @return mixed
	 */
	public function getActualCheckOut() {
		return $this->actual_check_out;
	}

	/**
	 * @param mixed $actual_check_out
	 */
	public function setActualCheckOut($actual_check_out) {
		$this->actual_check_out = $actual_check_out;
	}

	/**
	 * @return string
	 */
	public function getBookingDetailStatus(): string {
		return $this->booking_detail_status;
	}

	/**
	 * @param string $booking_detail_status
	 */
	public function setBookingDetailStatus(string $booking_detail_status) {
		$this->booking_detail_status = $booking_detail_status;
	}

	/**
	 * @return int
	 */
	public function getEmployeeId(): int {
		return $this->employee_id;
	}

	/**
	 * @param int $employee_id
	 */
	public function setEmployeeId(int $employee_id) {
		$this->employee_id = $employee_id;
	}

	/**
	 * @return string
	 */
	public function getEmployeeName(): string {
		return $this->employee_name;
	}

	/**
	 * @param string $employee_name
	 */
	public function setEmployeeName(string $employee_name) {
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
	 * @return int
	 */
	public function getSalesId(): int {
		return $this->sales_id;
	}

	/**
	 * @param int $sales_id
	 */
	public function setSalesId(int $sales_id) {
		$this->sales_id = $sales_id;
	}

	/**
	 * @return string
	 */
	public function getSalesName(): string {
		return $this->sales_name;
	}

	/**
	 * @param string $sales_name
	 */
	public function setSalesName(string $sales_name) {
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
	 * @return int
	 */
	public function getPriceSystemId(): int {
		return $this->price_system_id;
	}

	/**
	 * @param int $price_system_id
	 */
	public function setPriceSystemId(int $price_system_id) {
		$this->price_system_id = $price_system_id;
	}

	/**
	 * @return string
	 */
	public function getPriceSystemName(): string {
		return $this->price_system_name;
	}

	/**
	 * @param string $price_system_name
	 */
	public function setPriceSystemName(string $price_system_name) {
		$this->price_system_name = $price_system_name;
	}

	/**
	 * @return float
	 */
	public function getSourcePrice(): float {
		return $this->source_price;
	}

	/**
	 * @param float $source_price
	 */
	public function setSourcePrice(float $source_price) {
		$this->source_price = $source_price;
	}

	/**
	 * @return float
	 */
	public function getTotalPrice(): float {
		return $this->total_price;
	}

	/**
	 * @param float $total_price
	 */
	public function setTotalPrice(float $total_price) {
		$this->total_price = $total_price;
	}

	/**
	 * @return string
	 */
	public function getClient(): string {
		return $this->client;
	}

	/**
	 * @param string $client
	 */
	public function setClient(string $client) {
		$this->client = $client;
	}

	/**
	 * @return string
	 */
	public function getValid(): string {
		return $this->valid;
	}

	/**
	 * @param string $valid
	 */
	public function setValid(string $valid) {
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