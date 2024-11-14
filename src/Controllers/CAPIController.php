<?php

namespace Focuson\MPSFW\Controllers;

use FacebookAds\Api;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\UserData;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\EventRequest;

class CAPIController
{
    protected $api;
    protected $pixel_id;
	protected $test_event_code;

    public function __construct()
	{
		$access_token = get_option('focuson_mpsfw_access_token');
        $pixel_id = get_option('focuson_mpsfw_pixel_id');
		$this->test_event_code = get_option('focuson_mpsfw_test_event_code');

		if (!$access_token || !$pixel_id) {
			return null;
		}

        Api::init(null, null, $access_token);
        $this->api = Api::instance();
        $this->pixel_id = $pixel_id;
    }

    public function sendEvent($event_name, $user_data_array, $custom_data_array, $event_id = null)
    {
		$user_data = $this->createUserData($user_data_array);

        $custom_data = $this->createCustomData($custom_data_array);

        $event = (new Event())
            ->setEventName($event_name)
            ->setEventTime(time())
			->setEventId($event_id)
            ->setUserData($user_data)
            ->setCustomData($custom_data)
            ->setActionSource('website')
		;

        $event_request = (new EventRequest($this->pixel_id))
            ->setEvents([$event])
		;

		if ($this->test_event_code) {
			$event_request->setTestEventCode($this->test_event_code);
		}

        try {
            $response = $event_request->execute();
            return $response;
        } catch (\Exception $e) {
            error_log('CAPI Error: ' . $e->getMessage());
            return false;
        }
    }

	protected function createUserData($data) {
		$user_data = new UserData();
	
		$hash_fields = ['email', 'phone', 'first_name', 'last_name', 'city', 'state', 'country', 'zip'];
		$non_hash_fields = ['client_ip_address', 'client_user_agent', 'fbc', 'fbp'];
	
		foreach ($hash_fields as $field) {
			if (!empty($data[$field])) {
				$method = 'set' . ucfirst(str_replace('_', '', $field)); // Dinamicamente crea il nome del metodo
				if (method_exists($user_data, $method)) {
					$user_data->{$method}(hash('sha256', $data[$field]));
				}
			}
		}
	
		foreach ($non_hash_fields as $field) {
			if (!empty($data[$field])) {
				$method = 'set' . ucfirst(str_replace('_', '', $field));
				if (method_exists($user_data, $method)) {
					$user_data->{$method}($data[$field]);
				}
			}
		}
	
		return $user_data;
	}

	protected function createCustomData($data) {
		$custom_data = new CustomData();
	
		$methods = [
			'currency'   => 'setCurrency',
			'value'      => 'setValue',
			'contents'   => 'setContents',
			'order_id'   => 'setOrderId',
			'contentType'=> 'setContentType',
		];
	
		foreach ($methods as $key => $method) {
			if (!empty($data[$key])) {
				if ($key === 'contents' && is_array($data[$key])) {
					$contents = [];
					foreach ($data[$key] as $content) {
						$content_obj = (new \FacebookAds\Object\ServerSide\Content())
							->setProductId($content['id'])
							->setQuantity($content['quantity'])
							->setItemPrice($content['item_price']);
						$contents[] = $content_obj;
					}
					$custom_data->{$method}($contents);
				} else {
					$custom_data->{$method}($data[$key]);
				}
			}
		}
	
		return $custom_data;
	}
	
}
