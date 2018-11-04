<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/8/29
 * Time: 21:22
 */

namespace wise;
class LoginEmployeeModel extends \Entity {
    protected $employeeInfo = null;
    protected $employeeMenu = array();
    protected $employeeChannel = array();
    protected $channelSettingList = array();
    protected $channelSetting = null;

    /**
     * @return mixed
     */
    public function getEmployeeInfo(): Employee {
        if (empty($this->employeeInfo) || !is_object($this->employeeInfo)) $this->employeeInfo = new Employee();
        return $this->employeeInfo;
    }

    /**
     * @param mixed $employeeInfo
     */
    public function setEmployeeInfo($employeeInfo) {
        $this->employeeInfo = $employeeInfo;
    }

    /**
     * @return array
     */
    public function getEmployeeMenu(): array {
        return $this->employeeMenu;
    }

    /**
     * @param array $employeeMenu
     */
    public function setEmployeeMenu(array $employeeMenu) {
        $this->employeeMenu = $employeeMenu;
    }

    /**
     * @return array
     */
    public function getEmployeeChannel(): array {
        return $this->employeeChannel;
    }

    /**
     * @param array $employeeChannel
     */
    public function setEmployeeChannel(array $employeeChannel) {
        $this->employeeChannel = $employeeChannel;
    }

    /**
     * @return array
     */
    public function getChannelSettingList(): array {
        return $this->channelSettingList;
    }

    /**
     * @param array $channelSettingList
     */
    public function setChannelSettingList(array $channelSettingList) {
        $this->channelSettingList = $channelSettingList;
    }

    /**
     * @return null
     */
    public function getChannelSetting(int $channel_id) : Channel_settingEntity {
        return new Channel_settingEntity($this->channelSettingList[$channel_id]);
    }

    /**
     * @param null $channelSetting
     */
    public function setChannelSetting(Channel_settingEntity $ChannelSetting) {
        $this->channelSettingList[$ChannelSetting->getChannelId()] = $ChannelSetting->getPrototype();
    }





}

class Employee extends \Entity {
    protected $employee_id = 0;
    protected $company_id = 0;
    protected $employee_name = '';
    protected $photo = '';

    /**
     * @return mixed
     */
    public function getEmployeeId(): int {
        return $this->employee_id;
    }

    /**
     * @param mixed $employee_id
     */
    public function setEmployeeId($employee_id) {
        $this->employee_id = $employee_id;
    }

    /**
     * @return mixed
     */
    public function getCompanyId(): int {
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
    public function getEmployeeName() {
        return $this->employee_name;
    }

    /**
     * @param mixed $employee_name
     */
    public function setEmployeeName($employee_name) {
        $this->employee_name = $employee_name;
    }

    /**
     * @return mixed
     */
    public function getPhoto(): string {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
    }

}

