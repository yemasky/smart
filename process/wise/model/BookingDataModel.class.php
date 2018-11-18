<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/11/17
 * Time: 21:51
 */

namespace wise;


class BookingDataModel extends \Entity {
    protected $BookingEntity = null;
    protected $BookDetailList = [];
    protected $BookingDetailConsumeList = [];

    /**
     * @return null
     */
    public function getBookingEntity() {
        return $this->BookingEntity;
    }

    /**
     * @param null $BookingEntity
     */
    public function setBookingEntity($BookingEntity): void {
        $this->BookingEntity = $BookingEntity;
    }

    /**
     * @return array
     */
    public function getBookDetailList(): array {
        return $this->BookDetailList;
    }

    /**
     * @param array $BookDetailList
     */
    public function setBookDetailList(array $BookDetailList): void {
        $this->BookDetailList = $BookDetailList;
    }

    /**
     * @return array
     */
    public function getBookingDetailConsumeList(): array {
        return $this->BookingDetailConsumeList;
    }

    /**
     * @param array $BookingDetailConsumeList
     */
    public function setBookingDetailConsumeList(array $BookingDetailConsumeList): void {
        $this->BookingDetailConsumeList = $BookingDetailConsumeList;
    }


}