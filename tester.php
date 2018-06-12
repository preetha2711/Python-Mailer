<?php

class CashfreePaymentModuleFrontController extends ModuleFrontController {
	public $ssl = true;
	
	public function init() {
		parent::init();
	}
	
	public function initContent() {
		parent::initContent();
		
		global $smarty, $cart;

		$bill_address = new Address(intval($cart->id_address_invoice));
		$customer = new Customer(intval($cart->id_customer));

		if (!Validate::isLoadedObject($bill_address) OR ! Validate::isLoadedObject($customer))
			return $this->l("Cashfree error: (invalid address or customer)");
		$secretKey = "secret_key";
		$time = strtotime("now");
		
		$order_id = intval($cart->id);
		$customerName = strval($customer->firstname.' '.$customer->lastname);
		$customerPhone = "0000000000"; //not sending data. 
		$customerEmail = strval($customer->email);		
		$returnUrl = "http://localhost:8888/en/module/cashfree/validation"; //allows redirection to the Cashfree page
		$notifyUrl = "http://localhost:8888/en/module/cashfree/validation"; //allows redirection to the Cashfree page 

		$amount = $cart->getOrderTotal(true, Cart::BOTH);
		$orderCurrency = "INR";
		$orderNote = "PrestaShop order";

		$appId = Configuration::get("Cashfree_MERCHANT_ID");
		
		$secretKey = Configuration::get("Cashfree_MERCHANT_KEY");
		
		$secretKey = "2279c0ffb9550ad0f9e0652741c8d06a49409517";
		
		$postData = array( 
		"appId" => $appId, 
		"orderId" => $order_id, 
		"orderAmount" => $amount,
		"orderCurrency" => $orderCurrency, 
		"customerName" => $customerName, 
		"customerPhone" => $customerPhone, 
		"customerEmail" => $customerEmail,
		"returnUrl" => $returnUrl,
		"notifyUrl" => $notifyUrl, 
		);	

		ksort($postData);
		$signatureData = "";
		foreach ($postData as $key => $value){
			 $signatureData .= $key.$value;
		}
		$signature = hash_hmac('sha256', $signatureData, $secretKey,true);
		$signature = base64_encode($signature);
	   
		$postData["signature"] = $signature;
		
		$post_variables = $postData;


		$smarty->assign(
						array(
							"cashfree_post" => $post_variables,
							"action" => Configuration::get("Cashfree_GATEWAY_URL")
							)
					);
		
		// return $this->display(__FILE__, 'cashfree.tpl');
		$this->setTemplate('module:cashfree/views/templates/front/payment_form.tpl');
	}
}
