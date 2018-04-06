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

			$item = new OrderDataItem();
			$item->setProp('type', 'line_item');
			$item->setProp('name',$item->get_name());
			$item->setProp('unitPriceTaxExc',$this->order->get_item_total($item,false));
			$item->setProp('unitPriceTaxInc',$this->order->get_item_total($item,true));
			$item->setProp('quantity',$item->get_quantity());
			$item->setProp('vatRate', $tax);
			$item->setProp('taxTotal', $item->get_total_tax());
			$item->setProp('taxClass', $item->get_tax_class());

			$items[] = $item;
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

			$item = new OrderDataItem();
			$item->setProp('type', 'shipping');
			$item->setProp('name',$item->get_name());
			$item->setProp('unitPriceTaxExc', $item->get_total());
			$item->setProp('unitPriceTaxInc', $item->get_total() + $item->get_total_tax());
			$item->setProp('quantity',$item->get_quantity());
			$item->setProp('vatRate', $tax);
			$item->setProp('taxTotal', $item->get_total_tax());
			$item->setProp('taxClass', $item->get_tax_class());

			$items[] = $item;
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


			$item = new OrderDataItem();
			$item->setProp('type', 'fee');
			$item->setProp('name',$item->get_name());
			$item->setProp('unitPriceTaxExc', $item->get_total());
			$item->setProp('unitPriceTaxInc', $item->get_total() + $item->get_total_tax());
			$item->setProp('quantity',$item->get_quantity());
			$item->setProp('vatRate', $tax);
			$item->setProp('taxTotal', $item->get_total_tax());
			$item->setProp('taxClass', $item->get_tax_class());

			$items[] = $item;
		}

		return $items;
	}


}

