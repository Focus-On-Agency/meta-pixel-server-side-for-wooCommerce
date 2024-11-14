<?php

namespace Focuson\MPSFW\Providers;

use Focuson\MPSFW\Controllers\ClientController;
use Focuson\MPSFW\Controllers\EventHandleController;
use Focuson\MPSFW\Support\BaseServiceProvider;

class EventProvider extends BaseServiceProvider
{
    public function register()
	{
        add_action('woocommerce_thankyou', [EventHandleController::class, 'handleOrderCompleted']);
        add_action('woocommerce_add_to_cart', [EventHandleController::class, 'handleAddToCart'], 10, 6);
		add_action('woocommerce_before_single_product', [EventHandleController::class, 'handleProductView']);
        add_action('woocommerce_checkout_init', [EventHandleController::class, 'handleCheckoutInitiation']);
        add_action('woocommerce_product_search', [EventHandleController::class, 'handleSearch']);
        add_action('user_register', [EventHandleController::class, 'handleCompleteRegistration']);

        //add_action('wp_head', [ClientController::class, 'addScriptsHeader']);

        add_action('wp_footer', [EventHandleController::class, 'handlePageView']);
    }
}
