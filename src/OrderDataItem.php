<?php
namespace WPProgramator\WooCommerce;

class OrderDataItem {
	public $type;
	public $name;
	public $unitPriceTaxExc;
	public $unitPriceTaxInc;
	public $quantity;
	public $vatRate;
	public $taxTotal;
	public $taxClass;

	function __construct() {

	}

	function set_prop($prop, $value) {
		$this->{$prop} = $value;
	}
}
