<?php 


namespace App\Http\Controllers;

class cURL extends Controller {

	public $flag = false;
	public $url;
	public $method;
	public $headers = [];
	public $params;
	public $response;
	public $data;
	public $error;
	public $errorno;
	public $status;

	private $ch;
	public $header;
	public $request;

	function __construct () {
		$ch = curl_init();
		
		$this->ch = $ch;
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
	}

	private static $options = [
		'post'                 => false,
		'returntransfer'     => true,
		'ssl_verifier'         => false,
	];

	public static function url (string $url) {
		$newObj 		= new self;
		$newObj->url 	= $url;
		
		curl_setopt($newObj->ch, CURLOPT_URL, $url);

		return $newObj;
	}

	public function headers (...$headers) {
		
		if (empty($headers)) {
			return $this;
		}

		if (isset($headers[0]) && is_array($headers[0])) {
			$headers = $headers[0];
		}

		foreach ($headers as $header) {
			$this->headers[] = $header;
		}


		return $this;
	}

	public function json ($params) {

		if (!in_array('Content-Type: application/json', $this->headers)) {
			$this->headers[] = 'Content-Type: application/json';
		}

		$this->params = json_encode($params);
		return $this;

	}

	public function params ($params) {

		$this->params = $params;
		return $this;

	}
	
	public function post () {
		
		$this->method = 'POST';
		curl_setopt($this->ch, CURLOPT_POST, true);
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->params);

		$this->send();
		return $this;
	}

	public function put () {
		
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->params);
		
		$this->send();
		return $this;

	}

	public function get () {

		$this->method = 'GET';

		$this->send();

		return $this;

	}

	public function delete () {
		
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->params);
		
		$this->send();
		return $this;

	}

	private function send () {
		
		
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->headers);

		$this->response  = curl_exec($this->ch);

		if (curl_errno($this->ch)) {
			$returnData->flag     = false;
			$this->error          = curl_error($ch);
			$this->errorno        = curl_errno($ch);
			curl_close($this->ch);
			return 0;
		}

		$resultStatus       = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		$resultType         = curl_getinfo($this->ch, CURLINFO_CONTENT_TYPE);
		$isResultJSON       = strpos($resultType, 'application/json') !== false  ? 1 : 0;

		if ($isResultJSON) {
			$this->data = json_decode($this->response);
		}

		$this->status = $resultStatus;

		curl_close($this->ch);
		return 0;
	}

	public static function execute($url, $header = [], $data = NULL, $option = NULL){
		$isJSON     = false;
		$postData     = NULL;
		
		if($data && !isset($header['Content-Type'])){
			$header['Content-Type']  = 'application/json';
		}

		if(isset($header['Content-Type']) && $header['Content-Type'] == 'application/json'){
			$isJSON = true;
		}

		$headers = [];
		foreach($header as $key => $value){
			$headers[] = $key .':'. $value;
		}

		$ssl_verifier     	= isset($option['ssl_verifier']) 	? $option['ssl_verifier'] :     self::$options['ssl_verifier'];
		$returntransfer 	= isset($option['returntransfer'])  ? $option['returntransfer'] :     self::$options['returntransfer'];



		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returntransfer);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifier);

		if (isset($option['method'])) {
			switch ($option['method']) {
				case 'PATCH': 
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
					break;
			}
		}
		
		if($data){
			$postData = $isJSON? json_encode($data): http_build_query($data);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		}

		$response         = curl_exec($ch);
		$returnData     = new cURL;

		if(curl_errno($ch)){
			$returnData->flag           = false;
			$returnData->url            = $url;
			$returnData->headers         = $headers;
			$returnData->request        = $postData;
			$returnData->error          = curl_error($ch);
			$returnData->errorno        = curl_errno($ch);
		} else {
			$resultStatus       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$resultType         = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			$isResultJSON       = strpos($resultType, 'application/json') !== false  ? 1 : 0;
			
			if($resultStatus == 200){
				$returnData->flag         	= true;
				$returnData->url         	= $url;
				$returnData->headers     	= $headers;
				$returnData->request     	= $postData;
				$returnData->response     	= $response;
				$returnData->data         	= $isResultJSON? json_decode($response): $response;
			} else {
				$returnData->flag 		= false;
				$returnData->url 		= $url;
				$returnData->headers 	= $headers;
				$returnData->request 	= $postData;
				$returnData->response 	= $response;
				$returnData->data 		= $isResultJSON? json_decode($response): $response;
				$returnData->status 	= $resultStatus;
				$returnData->errorno 	= curl_errno($ch);
				$returnData->error 		= curl_error($ch);
			}
		}

		curl_close($ch);
		
		return $returnData;
	}
}

?>