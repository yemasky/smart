<?php
/**
 * Created by PhpStorm.
 * User: YEMASKY
 * Date: 2019/8/7
 * Time: 17:37
 */

namespace wise;


class TableStateModel extends \Entity {
    protected $Pagination = null;
    protected $Search = null;
    protected $Sort = null;
    protected $itemData = [];

    public function setPrototype(array $array) {
        foreach($array as $key => $value) {
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            } else {
                $functionKey = ucfirst($key);
                if(property_exists($this, $functionKey)) {
                    if(empty($value)) $value = array();
                    $function = 'set' . $functionKey;
                    $this->{$function}($value);
                }
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPagination(): Pagination {
        if(empty($this->Pagination)) $this->Pagination = new Pagination();
        return $this->Pagination;
    }

    /**
     * @param mixed $Pagination
     */
    public function setPagination($Pagination) {
        $this->Pagination = new Pagination($Pagination);
    }

    /**
     * @return mixed
     */
    public function getSearch(): Search {
        if(empty($this->Search)) $this->Search = new Search();
        return $this->Search;
    }

    /**
     * @param mixed $Search
     */
    public function setSearch($Search) {
        $this->Search = new Search($Search);
    }

    /**
     * @return mixed
     */
    public function getSort() : Sort {
        if(empty($this->Sort)) $this->Sort = new Sort();
        return $this->Sort;
    }

    /**
     * @param mixed $Sort
     */
    public function setSort($Sort) {
        $this->Sort = new Sort($Sort);
    }

    /**
     * @return array
     */
    public function getItemData(): array {
        return $this->itemData;
    }

    /**
     * @param array $itemData
     */
    public function setItemData(array $itemData) {
        $this->itemData = $itemData;
    }

}

class Pagination extends \Entity {
    protected $number = 10;
    protected $numberOfPages = 0;
    protected $start = 0;
    protected $totalItemCount = 0;

    /**
     * @return int
     */
    public function getNumber(): int {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number) {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): int {
        return $this->numberOfPages;
    }

    /**
     * @param int $numberOfPages
     */
    public function setNumberOfPages(int $numberOfPages) {
        $this->numberOfPages = $numberOfPages;
    }

    /**
     * @return int
     */
    public function getStart(): int {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart(int $start) {
        $this->start = $start;
    }

    /**
     * @return int
     */
    public function getTotalItemCount(): int {
        return $this->totalItemCount;
    }

    /**
     * @param int $totalItemCount
     */
    public function setTotalItemCount(int $totalItemCount) {
        $this->totalItemCount = $totalItemCount;
    }
}

class Search extends \Entity {
    protected $predicateObject;

    /**
     * @return mixed
     */
    public function getPredicateObject() {
        return $this->predicateObject;
    }

    /**
     * @param mixed $predicateObject
     */
    public function setPredicateObject($predicateObject) {
        $this->predicateObject = $predicateObject;
    }
}

class Sort extends \Entity {
    protected $predicate;
    protected $reverse = false;

    /**
     * @return mixed
     */
    public function getPredicate() {
        return $this->predicate;
    }

    /**
     * @param mixed $predicate
     */
    public function setPredicate($predicate) {
        $this->predicate = $predicate;
    }

    /**
     * @return bool
     */
    public function isReverse(): bool {
        return $this->reverse;
    }

    /**
     * @param bool $reverse
     */
    public function setReverse(bool $reverse) {
        $this->reverse = $reverse;
    }
}