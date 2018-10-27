<?php
/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2018/8/31
 * Time: 22:57
 */

namespace wise;

class BookingEntity extends \Entity {
	protected $book_id;
	protected $book_number = 0;
	protected $outer_book_number = '';
	protected $company_id;
	protected $channel = 'Hotel';
	protected $channel_id;
	protected $member_id = 0;
	protected $member_name = '';
	protected $member_mobile = '';
	protected $member_email = '';
	protected $booking_status = '0';
	protected $cash_pledge = 0.00;
	protected $employee_id;
	protected $employee_name = '';
	protected $business_day;
	protected $sales_id = 0;
	protected $sales_name = '';
	protected $booking_total_price = 0.00;
	protected $client = 'pms';
	protected $valid = '1';
	protected $add_datetime;

	public function __construct(array $arrayInput) {
		return $this->setPrototype($arrayInput);
	}

	/**
	 * @return mixed
	 */
	public function getBookId() {
		return $this->book_id;
	}

	/**
	 * @param mixed $book_id
	 */
	public function setBookId($book_id) {
		$this->book_id = $book_id;
	}

	/**
	 * @return string
	 */
	public function getBookNumber(): int {
		return $this->book_number;
	}

	/**
	 * @param string $book_number
	 */
	public function setBookNumber(int $book_number) {
		$this->book_number = $book_number;
	}

	/**
	 * @return string
	 */
	public function getOuterBookNumber(): string {
		return $this->outer_book_number;
	}

	/**
	 * @param string $outer_book_number
	 */
	public function setOuterBookNumber(string $outer_book_number) {
		$this->outer_book_number = $outer_book_number;
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
	 * @return string
	 */
	public function getMemberName(): string {
		return $this->member_name;
	}

	/**
	 * @param string $member_name
	 */
	public function setMemberName(string $member_name) {
		$this->member_name = $member_name;
	}

	/**
	 * @return string
	 */
	public function getMemberMobile(): string {
		return $this->member_mobile;
	}

	/**
	 * @param string $member_mobile
	 */
	public function setMemberMobile(string $member_mobile) {
		$this->member_mobile = $member_mobile;
	}

	/**
	 * @return string
	 */
	public function getMemberEmail(): string {
		return $this->member_email;
	}

	/**
	 * @param string $member_email
	 */
	public function setMemberEmail(string $member_email) {
		$this->member_email = $member_email;
	}

	/**
	 * @return string
	 */
	public function getBookingStatus(): string {
		return $this->booking_status;
	}

	/**
	 * @param string $booking_status
	 */
	public function setBookingStatus(string $booking_status) {
		$this->booking_status = $booking_status;
	}

	/**
	 * @return float
	 */
	public function getCashPledge(): float {
		return $this->cash_pledge;
	}

	/**
	 * @param float $cash_pledge
	 */
	public function setCashPledge(float $cash_pledge) {
		$this->cash_pledge = $cash_pledge;
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
	 * @return float
	 */
	public function getBookingTotalPrice(): float {
		return $this->booking_total_price;
	}

	/**
	 * @param float $booking_total_price
	 */
	public function setBookingTotalPrice(float $booking_total_price) {
		$this->booking_total_price = $booking_total_price;
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