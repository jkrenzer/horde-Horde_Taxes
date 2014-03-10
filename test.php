<?php

@define('HORDE_BASE', dirname(__FILE__) . '/../..');
require_once HORDE_BASE . '/lib/base.php';
require_once dirname(__FILE__) . '/Taxes.php';

$mapper = new Horde_TaxesMapper();
$taxes = $mapper->getAll();
var_dump($taxes);
