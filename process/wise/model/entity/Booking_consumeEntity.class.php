<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/9/3
 * Time: 0:37
 */

namespace wise;

class Booking_consumeEntity extends \Entity {
	protected $consume_id = null;
	protected $booking_detail_id = 0;
	protected $booking_number = "0";
	protected $booking_number_ext = '';
	protected $company_id;
	protected $channel;
	protected $booking_type;
	protected $channel_id;
	protected $member_id = 0;
	protected $market_father_id = 0;
	protected $market_id = 0;
	protected $market_name = '';
	protected $item_id = 0;
	protected $item_name = '';
	protected $item_category_id = 0;
	protected $item_category_name = '';
	protected $sales_id = 0;
	protected $sales_name = '';
	protected $discount_type = '0';
	protected $price_system_id = 0;
	protected $price_system_name = '';
	protected $original_price = 0.00;
	protected $consume_price = 0.00;
    protected $consume_price_total = 0.00;
	protected $employee_id = 0;
	protected $employee_name = '';
	protected $business_day;
	protected $confirm = '0';
	protected $confirm_employee_id = 0;
	protected $confirm_employee_name = '';
	protected $confirm_datetime;
	protected $valid = '1';
	protected $add_datetime;

	public function __construct(array $arrayInput = []) { if(!empty($arrayInput))  $this->setPrototype($arrayInput); }

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
     * @return int
     */
    public function getBookingDetailId(): int {
        return $this->booking_detail_id;
    }

    /**
     * @param int $booking_detail_id
     */
    public function setBookingDetailId(int $booking_detail_id) {
        $this->booking_detail_id = $booking_detail_id;
    }

    /**
     * @return int
     */
    public function getBookingNumber(): string {
        return $this->booking_number;
    }

    /**
     * @param int $booking_number
     */
    public function setBookingNumber(string $booking_number) {
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
	 * @return string
	 */
	public function getDiscountType(): string {
		return $this->discount_type;
	}

	/**
	 * @param string $discount_type
	 */
	public function setDiscountType(string $discount_type) {
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
	public function getOriginalPrice(): float {
		return $this->original_price;
	}

	/**
	 * @param float $original_price
	 */
	public function setOriginalPrice(float $original_price) {
		$this->original_price = $original_price;
	}

	/**
	 * @return float
	 */
	public function getConsumePrice(): float {
		return $this->consume_price;
	}

	/**
	 * @param float $consume_price
	 */
	public function setConsumePrice(float $consume_price) {
		$this->consume_price = $consume_price;
	}

    /**
     * @return float
     */
    public function getConsumePriceTotal(): float {
        return $this->consume_price_total;
    }

    /**
     * @param float $consume_price_total
     */
    public function setConsumePriceTotal(float $consume_price_total): void {
        $this->consume_price_total = $consume_price_total;
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
	 * @return string
	 */
	public function getConfirm(): string {
		return $this->confirm;
	}

	/**
	 * @param string $confirm
	 */
	public function setConfirm(string $confirm) {
		$this->confirm = $confirm;
	}

	/**
	 * @return int
	 */
	public function getConfirmEmployeeId(): int {
		return $this->confirm_employee_id;
	}

	/**
	 * @param int $confirm_employee_id
	 */
	public function setConfirmEmployeeId(int $confirm_employee_id) {
		$this->confirm_employee_id = $confirm_employee_id;
	}

	/**
	 * @return string
	 */
	public function getConfirmEmployeeName(): string {
		return $this->confirm_employee_name;
	}

	/**
	 * @param string $confirm_employee_name
	 */
	public function setConfirmEmployeeName(string $confirm_employee_name) {
		$this->confirm_employee_name = $confirm_employee_name;
	}

	/**
	 * @return mixed
	 */
	public function getConfirmDatetime() {
		return $this->confirm_datetime;
	}

	/**
	 * @param mixed $confirm_datetime
	 */
	public function setConfirmDatetime($confirm_datetime) {
		$this->confirm_datetime = $confirm_datetime;
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