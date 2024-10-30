<?php

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
class LuniteWpApiWrapper
{
    /**
     * @param $url
     * @param array $data
     * @param array $headers
     * @return mixed
     * Lunite Api Post Call Back Wrapper
     */
    public function luniteApiPostCallback($url, array$data, array $headers)
    {
        $args = array(
            'body' => $this->encodeRequestBody($data),
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $headers,
            'cookies' => array(),
        );
        $request = wp_remote_post($url, $args);

        return $this->decodeResponseBody(wp_remote_retrieve_body($request));
    }

    /**
     * @param array $body
     * @return false|string
     * Encode the Response
     */
    private function encodeRequestBody(array $body)
    {
        return json_encode($body);
    }

    /**
     * @param $body
     * @return mixed
     * Decode the Response
     */
    private function decodeResponseBody($body)
    {
        return json_decode($body, true);
    }
}
