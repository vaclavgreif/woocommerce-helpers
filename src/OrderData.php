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
	 * @return OrderDataItem[]
	 */
	function get_items() {
		$items = [];
		$line_items = $this->get_line_items();
		if( !empty( $line_items ) ){ $items = array_merge( $items, $line_items); }

		$shipping = $this->get_shipping_items();
		if( !empty( $shipping ) ){ $items = array_merge( $items, $shipping ); }

		$fees     = $this->get_fee_items();
		if( !empty( $fees ) ){ $items = array_merge( $items, $fees ); }

		return $items;
	}

	/**
	 * Get order Line items
	 * @return array
	 */
	function get_line_items() {
		$items = [];
		foreach ( $this->order->get_items() as $key => $item ) {
			/** @var $item \WC_Order_Item_Product */

			$tax = 0;
			if ($item->get_tax_status() == 'taxable') {
				if ($item->get_total_tax()) {
					$tax = round($item->get_total_tax() / ($item->get_total() / 100));
				}
			}

			$order_item = new OrderDataItem();
			$order_item->set_prop('type',$item->get_type());
			$order_item->set_prop('name',$item->get_name());
			$order_item->set_prop('unitPriceTaxExc',$this->order->get_item_total($item,false));
			$order_item->set_prop('unitPriceTaxInc',$this->order->get_item_total($item,true));
			$order_item->set_prop('quantity',$item->get_quantity());
			$order_item->set_prop('vatRate', $tax);
			$order_item->set_prop('taxTotal', $item->get_total_tax());
			$order_item->set_prop('taxClass', $item->get_tax_class());

			$items[] = $order_item;
		}

		return $items;

	}

	/**
	 * Get order shipping items
	 * @return array
	 */
	function get_shipping_items() {
		$items = [];
		foreach ( $this->order->get_items('shipping') as $key => $item ) {
			/** @var $item \WC_Order_Item_Shipping */

			$tax = 0;
			if ($item->get_tax_status() == 'taxable') {
				if ($item->get_total_tax()) {
					$tax = round($item->get_total_tax() / ($item->get_total() / 100));
				}
			}

			$order_item = new OrderDataItem();
			$order_item->set_prop('type',$item->get_type());
			$order_item->set_prop('name',$item->get_name());
			$order_item->set_prop('unitPriceTaxExc', $item->get_total());
			$order_item->set_prop('unitPriceTaxInc', $item->get_total() + $item->get_total_tax());
			$order_item->set_prop('quantity',$item->get_quantity());
			$order_item->set_prop('vatRate', $tax);
			$order_item->set_prop('taxTotal', $item->get_total_tax());
			$order_item->set_prop('taxClass', $item->get_tax_class());

			$items[] = $order_item;
		}

		return $items;
	}

	/**
	 * Get order fee items
	 * @return array
	 */
	function get_fee_items() {
		/** @var $item \WC_Order_Item_Fee */

		$items = [];

		foreach ( $this->order->get_items('fee') as $key => $item ) {
			$tax = 0;


			if ($item->get_tax_status() == 'taxable') {
				if ($item->get_total_tax()) {
					$tax = round($item->get_total_tax() / ($item->get_total() / 100));
				}
			}


			$order_item = new OrderDataItem();
			$order_item->set_prop('type',$item->get_type());
			$order_item->set_prop('name',$item->get_name());
			$order_item->set_prop('unitPriceTaxExc', $item->get_total());
			$order_item->set_prop('unitPriceTaxInc', $item->get_total() + $item->get_total_tax());
			$order_item->set_prop('quantity',$item->get_quantity());
			$order_item->set_prop('vatRate', $tax);
			$order_item->set_prop('taxTotal', $item->get_total_tax());
			$order_item->set_prop('taxClass', $item->get_tax_class());

			$items[] = $order_item;
		}

		return $items;
	}


}

