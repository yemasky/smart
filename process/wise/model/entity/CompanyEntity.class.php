<?php
/**
 * Created by PhpStorm.
 * User: QU
 * Date: 2018/11/3
 * Time: 17:00
 */

namespace wise;


class CompanyEntity extends \Entity {
    private $company_id = null;
    private $company_group = 0;
    private $group_unified_settings = 0;
    private $company_name = '';
    private $valid = '0';

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
     * @return int
     */
    public function getCompanyGroup(): int {
        return $this->company_group;
    }

    /**
     * @param int $company_group
     */
    public function setCompanyGroup(int $company_group): void {
        $this->company_group = $company_group;
    }

    /**
     * @return int
     */
    public function getGroupUnifiedSettings(): int {
        return $this->group_unified_settings;
    }

    /**
     * @param int $group_unified_settings
     */
    public function setGroupUnifiedSettings(int $group_unified_settings): void {
        $this->group_unified_settings = $group_unified_settings;
    }

    /**
     * @return string
     */
    public function getCompanyName(): string {
        return $this->company_name;
    }

    /**
     * @param string $company_name
     */
    public function setCompanyName(string $company_name): void {
        $this->company_name = $company_name;
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
    public function setValid(string $valid): void {
        $this->valid = $valid;
    }





}