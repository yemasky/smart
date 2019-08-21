<?php
/**
 * Created by PhpStorm.
 * User: CooC
 * Date: 2018/8/31
 * Time: 22:57
 */

namespace wise;

class BookingEntity extends \Entity {
	protected $booking_id = null;
	protected $booking_number = '0';
	protected $booking_number_ext = '';
	protected $company_id;
	protected $channel = 'Hotel';
	protected $channel_id;
	protected $receivable_id = 0;
	protected $member_id = 0;
	protected $member_name = '';
	protected $member_mobile = '';
	protected $member_email = '';
	protected $booking_status = '0';
	protected $cash_pledge = 0.00;
	protected $employee_id;
	protected $employee_name = '';
	protected $check_in = '0000-00-00 00:00:00';
	protected $in_time;
	protected $check_out = '0000-00-00 00:00:00';
	protected $out_time;
	protected $business_day;
	protected $sales_id = 0;
	protected $sales_name = '';
	protected $booking_total_price = 0.00;
	protected $client = 'pms';
	protected $valid = '1';
	protected $node = '';
	protected $add_datetime;
	protected $close_datetime;

	public function __construct(array $arrayInput = []) {
        if(!empty($arrayInput))  $this->setPrototype($arrayInput);
	}

    /**
     * @return mixed
     */
    public function getBookingId() {
        return $this->booking_id;
    }

    /**
     * @param mixed $booking_id
     */
    public function setBookingId($booking_id) {
        $this->booking_id = $booking_id;
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
    public function getReceivableId(): int {
        return $this->receivable_id;
    }

    /**
     * @param int $receivable_id
     */
    public function setReceivableId(int $receivable_id) {
        $this->receivable_id = $receivable_id;
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
    public function getInTime()
    {
        return $this->in_time;
    }

    /**
     * @param mixed $in_time
     */
    public function setInTime($in_time)
    {
        $this->in_time = $in_time;
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
    public function getOutTime()
    {
        return $this->out_time;
    }

    /**
     * @param mixed $out_time
     */
    public function setOutTime($out_time)
    {
        $this->out_time = $out_time;
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
     * @return string
     */
    public function getNode(): string {
        return $this->node;
    }

    /**
     * @param string $node
     */
    public function setNode(string $node) {
        $this->node = $node;
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
    public function getCloseDatetime()
    {
        return $this->close_datetime;
    }

    /**
     * @param mixed $close_datetime
     */
    public function setCloseDatetime($close_datetime)
    {
        $this->close_datetime = $close_datetime;
    }
	
}