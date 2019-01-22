<?php

namespace WPProgramator\WooCommerce;

class OrderDataItem {
	public $item_id;
	public $product_id;
	public $sku;
	public $variation_id;
	public $type;
	public $name;
	public $unitPriceTaxExc;
	public $unitPriceTaxInc;
	public $quantity;
	public $vatRate;
	public $taxTotal;
	public $taxClass;
	public $item;

	public function set_prop( $prop, $value ) {
		$this->{$prop} = $value;
	}
}
