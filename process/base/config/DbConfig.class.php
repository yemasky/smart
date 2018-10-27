<?php

/**
 * User: YEMASKY
 * Date: 2015/12/6
 * Time: 17:00
 */
abstract class DbConfig{
    const page_rows = 10;

    abstract function dsnRead();

    abstract function dsnWrite();
}