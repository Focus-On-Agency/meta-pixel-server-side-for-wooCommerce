<?php

namespace Focuson\MPSFW\Helper;

class Data
{
	static public function getUserData($user_id = null) : array
	{
		$current_user_id = $user_id ?? get_current_user_id();
		$email = get_user_meta($current_user_id, 'billing_email', true);

		return  [
			'email'     => $email ? hash('sha256', $email) : null,
			'phone'     => hash('sha256', get_user_meta($current_user_id, 'billing_phone', true)),
			'first_name'=> hash('sha256', get_user_meta($current_user_id, 'first_name', true)),
			'last_name' => hash('sha256', get_user_meta($current_user_id, 'last_name', true)),
			'city'      => hash('sha256', get_user_meta($current_user_id, 'billing_city', true)),
			'state'     => hash('sha256', get_user_meta($current_user_id, 'billing_state', true)),
			'country'   => hash('sha256', get_user_meta($current_user_id, 'billing_country', true)),
			'zip'       => hash('sha256', get_user_meta($current_user_id, 'billing_postcode', true)),
			'client_ip_address' => $_SERVER['REMOTE_ADDR'],
			'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
			'fbc'       => $_COOKIE['_fbc'] ?? null,
			'fbp'       => $_COOKIE['_fbp'] ?? null,
			'anon_id'	=> $current_user_id,
		];
	}

	static public function getOrderData($order_id) : array
	{
		$order = wc_get_order($order_id);
		if (!$order) {
			return [];
		}

		$contents = array_map(function ($item) {
			$product = $item->get_product();
			return [
				'id'        => $product->get_id(),
				'quantity'  => $item->get_quantity(),
				'item_price'=> $product->get_price(),
				'sku'       => $product->get_sku(),
				'category'  => $product->get_category_ids(),
			];
		}, $order->get_items());

		return [
			'currency'  => $order->get_currency(),
			'value'     => $order->get_total(),
			'contents'  => $contents,
			'order_id'  => $order->get_id(),
			'content_type' => 'product',
		];
	}


	static public function getProductData($product_id, $quantity = 1) : array
	{
		$product = wc_get_product($product_id);
		return [
			'id'        => $product->get_id(),
			'quantity'  => $quantity,
			'item_price'=> $product->get_price(),
			'sku'       => $product->get_sku(),
			'category'  => $product->get_category_ids(),
		];
	}
}
