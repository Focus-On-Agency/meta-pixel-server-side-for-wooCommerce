<?php

namespace Focuson\MPSFW\Controllers;

use Focuson\MPSFW\Helper\Data;

class ClientController
{
	static public function addScriptsHeader()
	{
		$pixel_id = get_option('focuson_mpsfw_pixel_id');

		if (!$pixel_id) {
			return;
		}

		?>
			<!-- Meta Pixel Code -->
			<script>
				!function(f,b,e,v,n,t,s)
				{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};
				if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
				n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t,s)}(window, document,'script',
				'https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '<?php echo $pixel_id; ?>');
			</script>
			<!-- End Meta Pixel Code -->
		<?php
	}

    static public function sendClientEvent($event_name, $user_data, $event_data, $event_id)
	{
		// Verifica che il pixel ID sia presente
		$pixel_id = get_option('focuson_mpsfw_pixel_id');
		if (!$pixel_id) {
			return;
		}

		// Controllo su contents (se esiste e non è vuoto)
		$content_ids = [];
		if (!empty($event_data['contents']) && is_array($event_data['contents'])) {
			$content_ids = array_column($event_data['contents'], 'id');
		}

		// Controlli su currency e value (valori di default se mancanti)
		$value = isset($event_data['value']) ? $event_data['value'] : 0;
		$currency = isset($event_data['currency']) ? $event_data['currency'] : 'USD';
		$content_type = isset($event_data['content_type']) ? $event_data['content_type'] : 'product'; // Default a 'product'

		?>
		<script>
			fbq('track', '<?php echo $event_name; ?>', {
				event_id: '<?php echo $event_id; ?>',
				client_ip_address: '<?php echo $user_data['client_ip_address'] ?? ''; ?>',
				client_user_agent: '<?php echo $user_data['client_user_agent'] ?? ''; ?>',
				fbc: '<?php echo $user_data['fbc'] ?? ''; ?>',
				fbp: '<?php echo $user_data['fbp'] ?? ''; ?>',
			});
		</script>

		<img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=<?php echo $pixel_id; ?>&ev=<?php echo $event_name; ?>&cd[content_ids]=<?php echo implode(',', $content_ids); ?>&cd[value]=<?php echo $value; ?>&cd[currency]=<?php echo $currency; ?>&cd[content_type]=<?php echo $content_type; ?>&cd[event_id]=<?php echo $event_id; ?>&cd[client_ip_address]=<?php echo $user_data['client_ip_address'] ?? ''; ?>&cd[client_user_agent]=<?php echo $user_data['client_user_agent'] ?? ''; ?>&cd[fbc]=<?php echo $user_data['fbc'] ?? ''; ?>&cd[fbp]=<?php echo $user_data['fbp'] ?? ''; ?>"
		/>
		<?php
	}

}

