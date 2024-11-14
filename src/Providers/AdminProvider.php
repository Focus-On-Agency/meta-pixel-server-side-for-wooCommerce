<?php

namespace Focuson\MPSFW\Providers;

use Focuson\MPSFW\Support\BaseServiceProvider;

class AdminProvider extends BaseServiceProvider
{
    protected $id;

    public function register() {
        $this->id = $this->app->config('focuson-mpsfw.id');

        add_filter('woocommerce_settings_tabs_array', [$this, 'addSettingsTab'], 50);
        add_action("woocommerce_settings_tabs_{$this->id}", [$this, 'renderSettingsPage']);
        add_action("woocommerce_update_options_{$this->id}", [$this, 'saveSettings']);
    }

    // Aggiungi il tab principale per le impostazioni Meta CAPI
    public function addSettingsTab($tabs) {
        $tabs["{$this->id}"] = __('Meta CAPI', 'focuson-mpsfw');
        return $tabs;
    }

    public function renderSettingsPage() {
        woocommerce_admin_fields($this->getSettings());
    }

    public function saveSettings() {
        woocommerce_update_options($this->getSettings());
    }

    // Impostazioni per Meta CAPI, inclusi "Match Rate Optimization" e "Tracciamento Anonimo"
    public function getSettings() {
        $settings = [
            [
                'title' => __('Meta CAPI Settings', 'focuson-mpsfw'),
                'type'  => 'title',
                'id'    => "{$this->id}_settings"
            ],
            [
                'title'    => __('Pixel ID', 'focuson-mpsfw'),
                'id'       => "{$this->id}_pixel_id",
                'type'     => 'text',
                'desc'     => __('Enter your Meta Pixel ID', 'focuson-mpsfw'),
                'desc_tip' => true,
            ],
            [
                'title'    => __('Access Token', 'focuson-mpsfw'),
                'id'       => "{$this->id}_access_token",
                'type'     => 'text',
                'desc'     => __('Enter your Meta Access Token', 'focuson-mpsfw'),
                'desc_tip' => true,
            ],
            [
                'title'    => __('Test Event Code', 'focuson-mpsfw'),
                'id'       => "{$this->id}_test_event_code",
                'type'     => 'text',
                'desc'     => __('Enter your Meta Test Event Code', 'focuson-mpsfw'),
                'desc_tip' => true,
            ],

            //[
            //    'title'    => __('Enable Anonymous Event Tracking', 'focuson-mpsfw'),
            //    'id'       => "{$this->id}_enable_anonymous",
            //    'type'     => 'checkbox',
            //    'desc'     => __('Allow tracking of events for users who block cookies or Meta Pixel', 'focuson-mpsfw'),
            //    'default'  => 'no',
            //],
            //[
            //    'title'    => __('Enable Match Rate Optimization', 'focuson-mpsfw'),
            //    'id'       => "{$this->id}_enable_match_rate",
            //    'type'     => 'checkbox',
            //    'desc'     => __('Optimize the match rate by sending hashed user data to Meta', 'focuson-mpsfw'),
            //    'default'  => 'yes',
            //],
            [
                'type' => 'sectionend',
                'id'   => "{$this->id}_settings_end"
            ]
        ];

        return $settings;
    }
}