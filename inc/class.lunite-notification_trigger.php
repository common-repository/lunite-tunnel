<?php


if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class LuniteActionsTrigger
{

    /**
     * @var false|mixed|void
     */
    private $sms_service;
    /**
     * @var false|mixed|void
     */

    public function __construct()
    {
        $this->sms_service = get_option('lunite_api_options_sms_carrier');
    }

    /**
     * @param $order_id
     */
    public function sendLuniteWCProcessingAlertToCustomer($order_id)
    {
        $this->luniteSendSms($order_id, 'processing');
    }

    /**
     * @param $order_id
     * @param $status
     * @todo Validate the Customer number is from lkr or other country
     */
    private function luniteSendSms($order_id, $status)
    {
        $order_details = new WC_Order($order_id);
        $message = $this->defaultOrderProcessingSms($order_id, 'Processing');
        $customer_phone_number = $this->reformatPhoneNumbers($order_details->get_billing_phone());
        $ada_dialog = new LuniteAdaDialogSmsApi();
        $ada_dialog->luniteSendAdaMessageReuqest($this->getSmsStartDate(), $this->getSmsEndDate($this->getSmsStartDate()), $customer_phone_number, $message);
    }


    /**
     * @return false|string
     * Get Sms Start Date
     */
    private function getSmsStartDate()
    {
        return $start_date = date("Y-m-d H:i:s");
    }

    /**
     * @param $start_date
     * @return false|string
     * Get Sms End Date
     */
    private function getSmsEndDate($start_date)
    {
        try {
            return date_format(
                date_add(new DateTime($start_date), date_interval_create_from_date_string('1 days')),
                "Y-m-d H:i:s"
            );
        } catch (\Exception $e) {
            $logger = new WC_Logger();
            $message = $e.'@at getSmsEndDate';
            $logger->add('new-woocommerce-log-name', $message);
        }
    }

    /**
     * @param $order_id
     * @param $order_status
     * @param null $shop_name
     * @return string
     * Order Processing Default Sms
     */
    private function defaultOrderProcessingSms($order_id, $order_status, $shop_name = null)
    {
        $shop_name = get_bloginfo('name');

        return 'Your order '.'#'.$order_id.' is now.'.$order_status.'. '.'Thank you for shopping at '.$shop_name.'.';
    }

    /**
     * @param $value
     * @return string|string[]|null
     * Reformat the phone number
     */
    private function reformatPhoneNumbers($value)
    {
        $number = preg_replace("/[^0-9]/", "", $value);
        if (strlen($number) == 9) {
            $number = "94" . $number;
        } elseif (strlen($number) == 10 && substr($number, 0, 1) == '0') {
            $number = "94" . ltrim($number, "0");
        } elseif (strlen($number) == 12 && substr($number, 0, 3) == '940') {
            $number = "94" . ltrim($number, "940");
        }

        return $number;
    }
}
