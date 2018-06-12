<?php
class CashfreeValidationModuleFrontController extends ModuleFrontController
{
	public $warning = '';
	public $message = '';
	public function initContent()
	{
		parent::initContent();
	
		$this->context->smarty->assign(array(
			'warning' => $this->warning,
			'message' => $this->message
		));

		$this->setTemplate('module:cashfree/views/templates/front/validation.tpl');
	}

	public function postProcess()
	{
		$merchant_id = Configuration::get('Cashfree_MERCHANT_ID');
		$secret_key = Configuration::get('Cashfree_MERCHANT_KEY');

		$orderId = $_POST["orderId"];
		$orderAmount = $_POST["orderAmount"];
		$referenceId = $_POST["referenceId"];
		$txStatus = $_POST["txStatus"];
		$paymentMode = $_POST["paymentMode"];
		$txMsg = $_POST["txMsg"];
		$txTime = $_POST["txTime"];
		$signature = $_POST["signature"];
		$data = $orderId.$orderAmount.$referenceId.$txStatus.$paymentMode.$txMsg.$txTime;

		$hash_hmac = hash_hmac('sha256', $data, $secret_key, true) ;
		$computedSignature = base64_encode($hash_hmac);
		if ($signature == $computedSignature) {
		   $isValid = true;
		 } else {
			 $isValid = false;
		}
		$cart = $this->context->cart;
		$cart_id = $cart->id;

		$amount = $cart->getOrderTotal(true,Cart::BOTH);
		$responseMsg1 = $_POST['txMsg'];

		if ($isValid == true) {

			if ($txStatus == "SUCCESS") {
					$status_code = "Ok";
					$message= $responseMsg1;
					$responseMsg= $responseMsg1;
					$status = Configuration::get('Cashfree_ID_ORDER_SUCCESS');
							
			}
			 else if ($txStatus == "CANCELLED") {
				$responseMsg = "Transaction has been cancelled. ";
				$message = $responseMsg1;
				$status = "6";
				$status_code = "Failed";
			} 
			else if ($txStatus == "PENDING") {
				$responseMsg = "Transaction is pending. ";
				$message = $responseMsg1;
				$status = "16";
				$status_code = "Payment Pending";
			} 
			else if ($txStatus == "FLAGGED") {
				$responseMsg = "Transaction has been flagged. ";
				$message = $responseMsg1;
				$status = "16";
				$status_code = "Payment Pending";
			}
			else
			{
				$responseMsg = "Transaction Failed. ";
				$message = $responseMsg1;
				$status = Configuration::get('Cashfree_ID_ORDER_FAILED');
				$status_code = "Failed";
			}			
			
		} 
		
		else {
			$status_code = "Failed";
			$responseMsg = "Security Error ..!";
			$message = $responseMsg1;
			$status = Configuration::get('Cashfree_ID_ORDER_FAILED');

		}
		//error_log("CASHFREE ORDER STATE ".Configuration::get('Cashfree_ID_ORDER_SUCCESS'));

		$customer = new Customer($cart->id_customer);

		$this->module->validateOrder((int)$cart_id,  $status, (float)$amount, "Cashfree", null, null, null, false, $customer->secure_key); //updating order status in order history of Prestashop

		if ($status == Configuration::get('Cashfree_ID_ORDER_SUCCESS')) {
		Tools::redirect('index.php?controller=order-confirmation&id_cart='.(int)$cart->id.'&id_module='.(int)$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key);
		} else {
			$this->message = $message;
			$this->warning= $responseMsg;
			$this->is_guest = $customer->is_guest;

			Tools::redirect('index.php');
	}
	}
}
