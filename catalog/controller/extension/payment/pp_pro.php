<?php
class ControllerExtensionPaymentPPPro extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/payment/pp_pro');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_start_date'] = $this->language->get('text_start_date');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_start_date'] = $this->language->get('entry_cc_start_date');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_issue'] = $this->language->get('entry_cc_issue');

		$data['help_start_date'] = $this->language->get('help_start_date');
		$data['help_issue'] = $this->language->get('help_issue');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['cards'] = array();

		$data['cards'][] = array(
			'text'  => 'Visa',
			'value' => 'VISA'
		);

		$data['cards'][] = array(
			'text'  => 'MasterCard',
			'value' => 'MASTERCARD'
		);

		$data['cards'][] = array(
			'text'  => 'Discover Card',
			'value' => 'DISCOVER'
		);

		$data['cards'][] = array(
			'text'  => 'American Express',
			'value' => 'AMEX'
		);

		$data['cards'][] = array(
			'text'  => 'Maestro',
			'value' => 'SWITCH'
		);

		$data['cards'][] = array(
			'text'  => 'Solo',
			'value' => 'SOLO'
		);

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_valid'] = array();

		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {
			$data['year_valid'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}
		
		$data['jwt'] = '';
		
		if ($this->config->get('pp_pro_cardinal_status')) {
			if (!$this->config->get('pp_pro_test')) {
				$data['cardinal_url'] = 'https://songbird.cardinalcommerce.com/edge/v1/songbird.js';
			} else {
				$data['cardinal_url'] = 'https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js';
			}
			
			$this->load->model('checkout/order');
		
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
			$amount = (int)((round($order_info['total'], 2)) * 100);
			$currency_code = 0;
		
			$iso_currencies = $this->getISOCurrencies();
				
			if (isset($iso_currencies[$order_info['currency_code']])) {
				$currency_code = $iso_currencies[$order_info['currency_code']];
			}
		
			$current_time = time();
			$expire_time = 3600; // expiration in seconds - this equals 1hr

			$jwt_data = array();
		
			$jwt_data['jti'] = uniqid();
			$jwt_data['iss'] = $this->config->get('pp_pro_cardinal_api_id');  // API Key Identifier
			$jwt_data['iat'] = $current_time; // JWT Issued At Time
			$jwt_data['exp'] = $current_time + $expire_time; // JWT Expiration Time
			$jwt_data['OrgUnitId'] = $this->config->get('pp_pro_cardinal_org_unit_id'); // Merchant's OrgUnit
			$jwt_data['ObjectifyPayload'] = true;
			$jwt_data['Payload'] = array(
				'OrderDetails' => array(
					'OrderNumber' => $this->session->data['order_id'],
					'Amount' => $amount,
					'CurrencyCode' => $currency_code
				)
			);
						
			require_once DIR_SYSTEM .'library/pp_pro/jwt.php';
		
			$jwt = new JWT($this->config->get('pp_pro_cardinal_api_key'));
		
			$data['jwt'] = $jwt->encode($jwt_data);
		}

		return $this->load->view('extension/payment/pp_pro', $data);
	}

	public function cca() {
		$this->load->language('extension/payment/pp_pro');
		
		if (utf8_strlen($this->request->post['cc_number']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['cc_number'] = $this->language->get('error_cc_number');
		}
		
		if (utf8_strlen($this->request->post['cc_cvv2']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['cc_cvv2'] = $this->language->get('error_cc_cvv2');
		}
		
		if (!$this->error) {					
			$data['cca'] = array(
				'Consumer' => array(
					'Account' => array(
						'AccountNumber' => $this->request->post['cc_number'],
						'ExpirationMonth' => $this->request->post['cc_expire_date_month'],
						'ExpirationYear' => $this->request->post['cc_expire_date_year']
					)
				)
			);
			
			// Save user's card data for later access
			$this->session->data['user_cc_number']              = $this->request->post['cc_number'];
			$this->session->data['user_cc_start_date_month']    = $this->request->post['cc_start_date_month'];
			$this->session->data['user_cc_start_date_year']     = $this->request->post['cc_start_date_year'];
			$this->session->data['user_cc_expire_date_month']   = $this->request->post['cc_expire_date_month'];
			$this->session->data['user_cc_expire_date_year']    = $this->request->post['cc_expire_date_year'];
			$this->session->data['user_cc_type']                = $this->request->post['cc_type'];
			$this->session->data['user_cc_issue']               = $this->request->post['cc_issue'];
			$this->session->data['user_cc_cvv2']                = $this->request->post['cc_cvv2'];
			
			$data['wait'] = true;
		}
		
		if ($this->error) {
			$data['confirm'] = true;
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function jwt() {
		$this->load->language('extension/payment/pp_pro');
										
		if (isset($this->request->post['jwt'])) {
			require_once DIR_SYSTEM .'library/pp_pro/jwt.php';
		
			$jwt = new JWT($this->config->get('pp_pro_cardinal_api_key'));
					
			$jwt_data = json_decode(json_encode($jwt->decode($this->request->post['jwt'])), true);
			
			if (isset($jwt_data['Payload']['ActionCode'])) {
				if ($jwt_data['Payload']['ActionCode'] == 'FAILURE') {
					$this->error['warning'] = $this->language->get('error_action_failure');
				}
				
				if ($jwt_data['Payload']['ActionCode'] == 'ERROR') {
					$this->error['warning'] = $this->language->get('error_action_error');
				}
			}
			
			if (isset($jwt_data['Payload']['Payment']['ExtendedData']['SignatureVerification']) && ($jwt_data['Payload']['Payment']['ExtendedData']['SignatureVerification'] != 'Y')) {
				$this->error['warning'] = $this->language->get('error_signature');
			}
				
			if (isset($jwt_data['Payload']['Payment']['ExtendedData']['PAResStatus']) && ($jwt_data['Payload']['Payment']['ExtendedData']['PAResStatus'] != 'Y') && ($jwt_data['Payload']['Payment']['ExtendedData']['PAResStatus'] != 'A')) {
				$this->error['warning'] = $this->language->get('error_pa_res');				
			}
								
			if (isset($jwt_data['Payload']['Payment']['ExtendedData']['Enrolled']) && ($jwt_data['Payload']['Payment']['ExtendedData']['Enrolled'] != 'Y')) {
				$this->error['warning'] = $this->language->get('error_enrolled');
			}
						
			if (isset($jwt_data['Payload']['ErrorNumber']) && ($jwt_data['Payload']['ErrorNumber']) && isset($jwt_data['Payload']['ErrorDescription']) && ($jwt_data['Payload']['ErrorDescription'])) {
				$this->error['warning'] = $jwt_data['Payload']['ErrorDescription'];
			}
		} else {
			if (isset($this->request->post['data'])) {
				$json_data = json_decode(htmlspecialchars_decode($this->request->post['data']), true);
			}
		
			if (isset($json_data['ErrorNumber']) && ($json_data['ErrorNumber']) && isset($json_data['ErrorDescription']) && ($json_data['ErrorDescription'])) {
				$this->error['warning'] = $json_data['ErrorDescription'];
			} else {
				$this->error['warning'] = $this->language->get('error_jwt');
			}
		}
		
		if ($this->error && isset($this->error['warning'])) {
			$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact'));
		}
		
		if (!$this->error) {
			$user_data['cc_number'] 							= $this->session->data['user_cc_number'];
			$user_data['cc_start_date_month'] 					= $this->session->data['user_cc_start_date_month'];
			$user_data['cc_start_date_year'] 					= $this->session->data['user_cc_start_date_year'];
			$user_data['cc_expire_date_month'] 					= $this->session->data['user_cc_expire_date_month'];
			$user_data['cc_expire_date_year'] 					= $this->session->data['user_cc_expire_date_year'];
			$user_data['cc_type'] 								= $this->session->data['user_cc_type'];
			$user_data['cc_issue'] 								= $this->session->data['user_cc_issue'];
			$user_data['cc_cvv2'] 								= $this->session->data['user_cc_cvv2'];
			
			$this->directPayment($user_data, $jwt_data);		
		}
				
		if (!$this->error) {
			$data['success'] = $this->url->link('checkout/success');
		}
		
		if ($this->error && isset($this->session->data['user_cc_number'])) {
			$data['confirm'] = true;
		}
		
		$data['error'] = $this->error;
		
		// Clear user's card data
		unset($this->session->data['user_cc_number']);
		unset($this->session->data['user_cc_start_date_month']);
		unset($this->session->data['user_cc_start_date_year']);
		unset($this->session->data['user_cc_expire_date_month']);
		unset($this->session->data['user_cc_expire_date_year']);
		unset($this->session->data['user_cc_type']);
		unset($this->session->data['user_cc_issue']);
		unset($this->session->data['user_cc_cvv2']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function send() {
		$this->load->language('extension/payment/pp_pro');
		
		if (utf8_strlen($this->request->post['cc_number']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['cc_number'] = $this->language->get('error_cc_number');
		}
		
		if (utf8_strlen($this->request->post['cc_cvv2']) < 1) {
			$this->error['warning'] = $this->language->get('error_warning');
			$this->error['cc_cvv2'] = $this->language->get('error_cc_cvv2');
		}
		
		if (!$this->error) {
			$user_data['cc_number'] 							= $this->request->post['cc_number'];
			$user_data['cc_start_date_month'] 					= $this->request->post['cc_start_date_month'];
			$user_data['cc_start_date_year'] 					= $this->request->post['cc_start_date_year'];
			$user_data['cc_expire_date_month'] 					= $this->request->post['cc_expire_date_month'];
			$user_data['cc_expire_date_year'] 					= $this->request->post['cc_expire_date_year'];
			$user_data['cc_type'] 								= $this->request->post['cc_type'];
			$user_data['cc_issue'] 								= $this->request->post['cc_issue'];
			$user_data['cc_cvv2'] 								= $this->request->post['cc_cvv2'];
						
			$this->directPayment($user_data);		
		}
				
		if (!$this->error) {
			$data['success'] = $this->url->link('checkout/success');
		}
		
		if ($this->error) {
			$data['confirm'] = true;
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	private function directPayment($user_data, $jwt_data = array()) {
		$this->load->language('extension/payment/pp_pro');
		
		// DoDirectPayment
		if (!$this->config->get('pp_pro_transaction')) {
			$payment_type = 'Authorization';
		} else {
			$payment_type = 'Sale';
		}
				
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			
		$version = '59.0';
						
		$request  = 'METHOD=DoDirectPayment';
		$request .= '&VERSION=' . $version;
			
		// Add the additional fields for the 3d secure
		if (isset($jwt_data['Payload']['Payment']['ExtendedData']['PAResStatus'])) {
			$request .= '&AUTHSTATUS3DS=' . $jwt_data['Payload']['Payment']['ExtendedData']['PAResStatus'];
		}
			
		if (isset($jwt_data['Payload']['Payment']['ExtendedData']['Enrolled'])) {
			$request .= '&MPIVENDOR3DS=' . $jwt_data['Payload']['Payment']['ExtendedData']['Enrolled'];
		}
			
		if (isset($jwt_data['Payload']['Payment']['ExtendedData']['CAVV'])) {
			$request .= '&CAVV=' . $jwt_data['Payload']['Payment']['ExtendedData']['CAVV'];
		}
			
		if (isset($jwt_data['Payload']['Payment']['ExtendedData']['ECIFlag'])) {
			$request .= '&ECI3DS=' . $jwt_data['Payload']['Payment']['ExtendedData']['ECIFlag'];
		}
			
		if (isset($jwt_data['Payload']['Payment']['ExtendedData']['XID'])) {
			$request .= '&XID=' . $jwt_data['Payload']['Payment']['ExtendedData']['XID'];
		}
		
		$request .= '&USER=' . urlencode($this->config->get('pp_pro_username'));
		$request .= '&PWD=' . urlencode($this->config->get('pp_pro_password'));
		$request .= '&SIGNATURE=' . urlencode($this->config->get('pp_pro_signature'));
		$request .= '&CUSTREF=' . (int)$order_info['order_id'];
		$request .= '&PAYMENTACTION=' . $payment_type;
		$request .= '&AMT=' . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		$request .= '&CREDITCARDTYPE=' . $user_data['cc_type'];
		$request .= '&ACCT=' . urlencode(str_replace(' ', '', $user_data['cc_number']));
		$request .= '&CARDSTART=' . urlencode($user_data['cc_start_date_month'] . $user_data['cc_start_date_year']);
		$request .= '&EXPDATE=' . urlencode($user_data['cc_expire_date_month'] . $user_data['cc_expire_date_year']);
		$request .= '&CVV2=' . urlencode($user_data['cc_cvv2']);
			
		if ($user_data['cc_type'] == 'SWITCH' || $user_data['cc_type'] == 'SOLO') {
			$request .= '&ISSUENUMBER=' . urlencode($user_data['cc_issue']);
		}

		$request .= '&FIRSTNAME=' . urlencode($order_info['payment_firstname']);
		$request .= '&LASTNAME=' . urlencode($order_info['payment_lastname']);
		$request .= '&EMAIL=' . urlencode($order_info['email']);
		$request .= '&PHONENUM=' . urlencode($order_info['telephone']);
		$request .= '&IPADDRESS=' . urlencode($this->request->server['REMOTE_ADDR']);
		$request .= '&STREET=' . urlencode($order_info['payment_address_1']);
		$request .= '&CITY=' . urlencode($order_info['payment_city']);
		$request .= '&STATE=' . urlencode(($order_info['payment_iso_code_2'] != 'US') ? $order_info['payment_zone'] : $order_info['payment_zone_code']);
		$request .= '&ZIP=' . urlencode($order_info['payment_postcode']);
		$request .= '&COUNTRYCODE=' . urlencode($order_info['payment_iso_code_2']);
		$request .= '&CURRENCYCODE=' . urlencode($order_info['currency_code']);
		$request .= '&BUTTONSOURCE=' . urlencode('OpenCart_3.0_WPP');
						
		if ($this->cart->hasShipping()) {
			$request .= '&SHIPTONAME=' . urlencode($order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname']);
			$request .= '&SHIPTOSTREET=' . urlencode($order_info['shipping_address_1']);
			$request .= '&SHIPTOCITY=' . urlencode($order_info['shipping_city']);
			$request .= '&SHIPTOSTATE=' . urlencode(($order_info['shipping_iso_code_2'] != 'US') ? $order_info['shipping_zone'] : $order_info['shipping_zone_code']);
			$request .= '&SHIPTOCOUNTRYCODE=' . urlencode($order_info['shipping_iso_code_2']);
			$request .= '&SHIPTOZIP=' . urlencode($order_info['shipping_postcode']);
		} else {
			$request .= '&SHIPTONAME=' . urlencode($order_info['payment_firstname'] . ' ' . $order_info['payment_lastname']);
			$request .= '&SHIPTOSTREET=' . urlencode($order_info['payment_address_1']);
			$request .= '&SHIPTOCITY=' . urlencode($order_info['payment_city']);
			$request .= '&SHIPTOSTATE=' . urlencode(($order_info['payment_iso_code_2'] != 'US') ? $order_info['payment_zone'] : $order_info['payment_zone_code']);
			$request .= '&SHIPTOCOUNTRYCODE=' . urlencode($order_info['payment_iso_code_2']);
			$request .= '&SHIPTOZIP=' . urlencode($order_info['payment_postcode']);
		}
								
		if (!$this->config->get('pp_pro_test')) {
			$curl = curl_init('https://api-3t.paypal.com/nvp');
		} else {
			$curl = curl_init('https://api-3t.sandbox.paypal.com/nvp');
		}
			
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
			
		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$this->log->write('DoDirectPayment failed for order  '. $this->session->data['order_id'] . ': ' . curl_error($curl) . '(' . curl_errno($curl) . ')');
		}
								
		$response_info = array();
				
		parse_str($response, $response_info);

		if (($response_info['ACK'] == 'Success') || ($response_info['ACK'] == 'SuccessWithWarning')) {
			$message = '';
				
			$message .= "3DS: YES \n";
									
			if (isset($response_info['AVSCODE'])) {
				$message .= 'AVSCODE: ' . $response_info['AVSCODE'] . "\n";
			}

			if (isset($response_info['CVV2MATCH'])) {
				$message .= 'CVV2MATCH: ' . $response_info['CVV2MATCH'] . "\n";
			}

			if (isset($response_info['TRANSACTIONID'])) {
				$message .= 'TRANSACTIONID: ' . $response_info['TRANSACTIONID'] . "\n";
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('pp_pro_order_status_id'), $message, false);
					
			$this->log->write('DoDirectPayment success for order ' . $this->session->data['order_id'] . ': '. print_r($response_info, true));
		} else {
			$this->log->write('DoDirectPayment Failure (inside directPayment()) for order ' . $this->session->data['order_id'] . ': ' . print_r($response_info, true));
				
			$this->error['warning'] = $response_info['L_LONGMESSAGE0'];
		}
	}
	
	private function getISOCurrencies() {
		return array(
			'AED' => '784', 'AFN' => '971',
			'ALL' => '008', 'AMD' => '051', 'ANG' => '532', 'AOA' => '973', 'ARS' => '032', 'AUD' => '036', 'AWG' => '533',
			'AZN' => '944', 'BAM' => '977', 'BBD' => '052', 'BDT' => '050', 'BGN' => '975', 'BHD' => '048', 'BIF' => '108',
			'BMD' => '060', 'BND' => '096', 'BOB' => '068', 'BOV' => '984', 'BRL' => '986', 'BSD' => '044', 'BTN' => '064',
			'BWP' => '072', 'BYR' => '974', 'BZD' => '084', 'CAD' => '124', 'CDF' => '976', 'CHE' => '947', 'CHF' => '756',
			'CHW' => '948', 'CLF' => '990', 'CLP' => '152', 'CNY' => '156', 'COP' => '170', 'COU' => '970', 'CRC' => '188',
			'CUC' => '931', 'CUP' => '192', 'CVE' => '132', 'CZK' => '203', 'DJF' => '262', 'DKK' => '208', 'DOP' => '214',
			'DZD' => '012', 'EEK' => '233', 'EGP' => '818', 'ERN' => '232', 'ETB' => '230', 'EUR' => '978', 'FJD' => '242',
			'FKP' => '238', 'GBP' => '826', 'GEL' => '981', 'GHS' => '936', 'GIP' => '292', 'GMD' => '270', 'GNF' => '324',
			'GTQ' => '320', 'GYD' => '328', 'HKD' => '344', 'HNL' => '340', 'HRK' => '191', 'HTG' => '332', 'HUF' => '348',
			'IDR' => '360', 'ILS' => '376', 'INR' => '356', 'IQD' => '368', 'IRR' => '364', 'ISK' => '352', 'JMD' => '388',
			'JOD' => '400', 'JPY' => '392', 'KES' => '404', 'KGS' => '417', 'KHR' => '116', 'KMF' => '174', 'KPW' => '408',
			'KRW' => '410', 'KWD' => '414', 'KYD' => '136', 'KZT' => '398', 'LAK' => '418', 'LBP' => '422', 'LKR' => '144',
			'LRD' => '430', 'LSL' => '426', 'LTL' => '440', 'LVL' => '428', 'LYD' => '434', 'MAD' => '504', 'MDL' => '498',
			'MGA' => '969', 'MKD' => '807', 'MMK' => '104', 'MNT' => '496', 'MOP' => '446', 'MRO' => '478', 'MUR' => '480',
			'MVR' => '462', 'MWK' => '454', 'MXN' => '484', 'MXV' => '979', 'MYR' => '458', 'MZN' => '943', 'NAD' => '516',
			'NGN' => '566', 'NIO' => '558', 'NOK' => '578', 'NPR' => '524', 'NZD' => '554', 'OMR' => '512', 'PAB' => '590',
			'PEN' => '604', 'PGK' => '598', 'PHP' => '608', 'PKR' => '586', 'PLN' => '985', 'PYG' => '600', 'QAR' => '634',
			'RON' => '946', 'RSD' => '941', 'RUB' => '643', 'RWF' => '646', 'SAR' => '682', 'SBD' => '090', 'SCR' => '690',
			'SDG' => '938', 'SEK' => '752', 'SGD' => '702', 'SHP' => '654', 'SLL' => '694', 'SOS' => '706', 'SRD' => '968',
			'STD' => '678', 'SYP' => '760', 'SZL' => '748', 'THB' => '764', 'TJS' => '972', 'TMT' => '934', 'TND' => '788',
			'TOP' => '776', 'TRY' => '949', 'TTD' => '780', 'TWD' => '901', 'TZS' => '834', 'UAH' => '980', 'UGX' => '800',
			'USD' => '840', 'USN' => '997', 'USS' => '998', 'UYU' => '858', 'UZS' => '860', 'VEF' => '937', 'VND' => '704',
			'VUV' => '548', 'WST' => '882', 'XAF' => '950', 'XAG' => '961', 'XAU' => '959', 'XBA' => '955', 'XBB' => '956',
			'XBC' => '957', 'XBD' => '958', 'XCD' => '951', 'XDR' => '960', 'XOF' => '952', 'XPD' => '964', 'XPF' => '953',
			'XPT' => '962', 'XTS' => '963', 'XXX' => '999', 'YER' => '886', 'ZAR' => '710', 'ZMK' => '894', 'ZWL' => '932',
		);
	}
}