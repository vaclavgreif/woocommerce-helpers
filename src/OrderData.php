<?php
namespace WPProgramator\WooCommerce;

class OrderData {
	protected $order_id;
	protected $order;
	function __construct($order_id)
	{
		$this->order_id = $order_id;
		$this->order = wc_get_order($this->order_id);
	}

	/**
	 * Get order items
	 * @return array
	 */
	function getItems() {
		$items = [];
		$line_items = $this->getLineItems();
		if( !empty( $line_items ) ){ $items = array_merge( $items, $line_items); }

		$shipping = $this->getShippingItems();
		if( !empty( $shipping ) ){ $items = array_merge( $items, $shipping ); }

		$fees     = $this->getFeeItems();
		if( !empty( $fees ) ){ $items = array_merge( $items, $fees ); }

		return $items;
	}

	/**
	 * Get order Line items
	 * @return array
	 */
	function getLineItems() {
		$items = [];
		foreach ( $this->order->get_items() as $key => $item ) {
			/** @var $item \WC_Order_Item_Product */

			$tax = 0;
			if ($item->get_tax_status() == 'taxable') {
				if ($item->get_total_tax()) {
					$tax = round($item->get_total_tax() / ($item->get_total() / 100));
				}
			}

			$items[] = [
				'name'       => $item->get_name(),
				'unit_price_tax_excl' => $this->order->get_item_total($item,false),
				'unit_price_tax_incl' => $this->order->get_item_total($item,true),
				'quantity' => $item->get_quantity(),
				'vat_rate' => $tax,
				'tax_total' => $item->get_total_tax()
			];
		}

		return $items;

	}

	/**
	 * Get order shipping items
	 * @return array
	 */
	function getShippingItems() {
		$items = [];
		foreach ( $this->order->get_items('shipping') as $key => $item ) {
			/** @var $item \WC_Order_Item_Shipping */

			$tax = 0;
			if ($item->get_tax_status() == 'taxable') {
				if ($item->get_total_tax()) {
					$tax = round($item->get_total_tax() / ($item->get_total() / 100));
				}
			}

			$items[] = [
				'name'       => $item->get_name(),
				'unit_price_tax_excl' => $item->get_total() ,
				'unit_price_tax_incl' => $item->get_total() + $item->get_total_tax(),
				'quantity' => $item->get_quantity(),
				'vat_rate' => $tax,
				'tax_total' => $item->get_total_tax()
			];
		}

		return $items;
	}

	/**
	 * Get order fee items
	 * @return array
	 */
	function getFeeItems() {
		/** @var $item \WC_Order_Item_Fee */

		$items = [];

		foreach ( $this->order->get_items('fee') as $key => $item ) {
			$tax = 0;


			if ($item->get_tax_status() == 'taxable') {
				if ($item->get_total_tax()) {
					$tax = round($item->get_total_tax() / ($item->get_total() / 100));
				}
			}

			$items[] = [
				'name'       => $item->get_name(),
				'unit_price_tax_excl' => $item->get_total(),
				'unit_price_tax_incl' => $item->get_total() + $item->get_total_tax(),
				'quantity' => $item->get_quantity(),
				'vat_rate' => $tax,
				'tax_total' => $item->get_total_tax()
			];
		}

		return $items;
	}


}

