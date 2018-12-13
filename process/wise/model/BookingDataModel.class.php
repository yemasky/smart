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
    protected $BookingAccountsList = [];
    protected $BookingDiscountList = [];

    /**
     * @return null
     */
    public function getBookingEntity() : BookingEntity {
        return $this->BookingEntity;
    }

    /**
     * @param null $BookingEntity
     */
    public function setBookingEntity($BookingEntity) {
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
    public function setBookDetailList(array $BookDetailList) {
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
    public function setBookingDetailConsumeList(array $BookingDetailConsumeList) {
        $this->BookingDetailConsumeList = $BookingDetailConsumeList;
    }

    /**
     * @return array
     */
    public function getBookingAccountsList(): array {
        return $this->BookingAccountsList;
    }

    /**
     * @param array $BookingAccountsList
     */
    public function setBookingAccountsList(array $BookingAccountsList) {
        $this->BookingAccountsList = $BookingAccountsList;
    }

    /**
     * @return array
     */
    public function getBookingDiscountList(): array {
        return $this->BookingDiscountList;
    }

    /**
     * @param array $BookingDiscountList
     */
    public function setBookingDiscountList(array $BookingDiscountList) {
        $this->BookingDiscountList = $BookingDiscountList;
    }


}