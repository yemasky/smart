<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/11/3
 * Time: 16:55
 */

namespace wise;


class Channel_settingEntity extends \Entity {
    protected $channel_id = 0;
    protected $channel = 'Hotel';
    protected $company_id = 0;
    protected $is_business_day;
    protected $check_in_time = '14:00:00';
    protected $check_out_time = '12:00:00';
    protected $half_price_time = '18:00:00';
    protected $plus_price_time = '06:00:00';
    protected $decimal_price = '0';

    /**
     * @return int
     */
    public function getChannelId(): int {
        return $this->channel_id;
    }

    /**
     * @param int $channel_id
     */
    public function setChannelId(int $channel_id): void {
        $this->channel_id = $channel_id;
    }

    /**
     * @return string
     */
    public function getChannel(): string {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void {
        $this->channel = $channel;
    }

    /**
     * @return int
     */
    public function getCompanyId(): int {
        return $this->company_id;
    }

    /**
     * @param int $company_id
     */
    public function setCompanyId(int $company_id): void {
        $this->company_id = $company_id;
    }

    /**
     * @return mixed
     */
    public function getisBusinessDay() {
        return $this->is_business_day;
    }

    /**
     * @param mixed $is_business_day
     */
    public function setIsBusinessDay($is_business_day): void {
        $this->is_business_day = $is_business_day;
    }

    /**
     * @return string
     */
    public function getCheckInTime(): string {
        return $this->check_in_time;
    }

    /**
     * @param string $check_in_time
     */
    public function setCheckInTime(string $check_in_time): void {
        $this->check_in_time = $check_in_time;
    }

    /**
     * @return string
     */
    public function getCheckOutTime(): string {
        return $this->check_out_time;
    }

    /**
     * @param string $check_out_time
     */
    public function setCheckOutTime(string $check_out_time): void {
        $this->check_out_time = $check_out_time;
    }

    /**
     * @return string
     */
    public function getHalfPriceTime(): string {
        return $this->half_price_time;
    }

    /**
     * @param string $half_price_time
     */
    public function setHalfPriceTime(string $half_price_time): void {
        $this->half_price_time = $half_price_time;
    }

    /**
     * @return string
     */
    public function getPlusPriceTime(): string {
        return $this->plus_price_time;
    }

    /**
     * @param string $plus_price_time
     */
    public function setPlusPriceTime(string $plus_price_time): void {
        $this->plus_price_time = $plus_price_time;
    }

    /**
     * @return string
     */
    public function getDecimalPrice(): string {
        return $this->decimal_price;
    }

    /**
     * @param string $decimal_price
     */
    public function setDecimalPrice(string $decimal_price): void {
        $this->decimal_price = $decimal_price;
    }
}