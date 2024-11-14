<?php

namespace Focuson\MPSFW\Controllers;

use Focuson\MPSFW\Helper\Data;

class EventHandleController
{
	static public function handleOrderCompleted($order_id) {
		$CAPI = new CAPIController();
		if(!$CAPI) {
			return;
		}

		$event_data = Data::getOrderData($order_id);
		$user_data = Data::getUserData();
		$event_id = uniqid('event_', true);

		$CAPI->sendEvent('Purchase', $user_data, $event_data, $event_id);

		//ClientController::sendClientEvent('Purchase', $user_data, $event_data, $event_id);
	}

	static public function handleAddToCart($cart_item_key, $product_id, $quantity, $variation_id = 0, $cart_item_data = [], $cart_item = [])
	{
		$CAPI = new CAPIController();
		if(!$CAPI) {
			return;
		}

		$contents = Data::getProductData($product_id, $quantity);
		$event_data = [
			'contents' => [$contents],
			'currency'  => get_woocommerce_currency(),
			'value'     => $contents['item_price'] * $quantity,
			'content_type' => 'product',
		];
		$user_data = Data::getUserData();
		$event_id = uniqid('event_', true);

		$CAPI->sendEvent('AddToCart', $user_data, $event_data, $event_id);

		//ClientController::sendClientEvent('AddToCart', $user_data, $event_data, $event_id);
	}

	static public function handleProductView() {
		$CAPI = new CAPIController();
		if(!$CAPI) {
			return;
		}

		if (is_product()) {
			global $product;
			if ($product instanceof \WC_Product) {
				$contents = Data::getProductData($product->get_id());
				$event_data = [
					'contents' => [$contents],
					'currency'  => get_woocommerce_currency(),
					'value'     => $product->get_price() * 1,
					'content_type' => 'product',
				];
				$user_data = Data::getUserData();
				$event_id = uniqid('event_', true);

				$CAPI->sendEvent('ViewContent', $user_data, $event_data, $event_id);
				//ClientController::sendClientEvent('ViewContent', $user_data, $event_data, $event_id);
			}
		}

	}

	static public function handleCheckoutInitiation() {
        $CAPI = new CAPIController();
		if(!$CAPI) {
			return;
		}

        $cart_data = WC()->cart->get_cart();
        $contents = array_map(function ($cart_item) {
            return Data::getProductData(
				$cart_item['product_id'],
				$cart_item['quantity']
		);}, $cart_data);

        $event_data = [
            'currency'  => get_woocommerce_currency(),
            'value'     => WC()->cart->total,
            'contents'  => $contents,
        ];
        $user_data = Data::getUserData();
		$event_id = uniqid('event_', true);

        $CAPI->sendEvent('InitiateCheckout', $user_data, $event_data, $event_id);

		//ClientController::sendClientEvent('InitiateCheckout', $user_data, $event_data, $event_id);
    }

    static public function handleSearch($search_term) {
        $CAPI = new CAPIController();
		if(!$CAPI) {
			return;
		}
        
        $event_data = [
            'search_string' => $search_term,
			'content_type' => 'search_results',
        ];
        $user_data = Data::getUserData();
		$event_id = uniqid('event_', true);

        $CAPI->sendEvent('Search', $user_data, $event_data, $event_id);

		//ClientController::sendClientEvent('Search', $user_data, $event_data, $event_id);
    }

    static public function handleCompleteRegistration($user_id) {
        $CAPI = new CAPIController();
		if(!$CAPI) {
			return;
		}

        $user_data = Data::getUserData($user_id);
		$event_data = [
			'content_type' => 'registration',
		];
		$event_id = uniqid('event_', true);

        $CAPI->sendEvent('CompleteRegistration', $user_data, $event_data, $event_id);
    }

	static public function handlePageView() {
		$CAPI = new CAPIController();

		if(!$CAPI) {
			return;
		}

		$event_data = [];
		$user_data = Data::getUserData();
		$event_id = uniqid('event_', true);

		$CAPI->sendEvent('PageView', $user_data, $event_data, $event_id);
	}
}
