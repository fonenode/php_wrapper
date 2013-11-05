<?php
/*
 * PHP wrapper for the Fonenode API (fonenode.com/docs)
 *
 * https://github.com/fonenode/php_wrapper
 *
 * @author Opeyemi Obembe (@kehers) <ope@fonenode.com>
 * @version 0.9.1
 */
 
require 'exceptions.php';

class fonenode {

	private $_error;
	private $_status;
	private $_auth_id;
	private $_auth_secret;
	public $_api_root = "http://api.fonenode.com/v1/";

	/*
	 * Constructor
	*/	
	public function __construct($id, $secret) {
		$this->_auth_id = $id;
		$this->_auth_secret = $secret;
	}
	
	/*
	 * Helpers
	*/
    
	/*
	 * Get last status code
	*/
	public function getStatusCode() {
		return $this->_status;
	}
	
	/*
	 * Get last error
	*/
	public function getError() {
		return $this->_error;
	}
	
	/*
	 * Responses
     * fonenode.com/docs#responses
	*/
    
	/*
	 * Create Response
	 * Create a response you can later attach to a call or number.
     * fonenode.com/docs#responses-create
	*/
	public function create_response() {
	}
	
	/*
	 * List Responses
	 * List responses you have created.
     * fonenode.com/docs#responses-list
	*/
	public function list_responses($limit = 20, $offset = 0) {
		$url = 'responses?';

		if ((int) $limit)
			$url .= 'limit='.$limit;
		if ((int) $limit && (int) $offset)
			$url .= '&';
		if ((int) $offset)
			$url .= 'offset='.$offset;
        

		$response = $this->get($url);
		return json_decode($response, true);
	}
	
	/*
	 * Get a response
     * fonenode.com/docs#responses-get
	*/
	public function get_response($id) {
        if (!$id)
			throw new MissingParameterException("Response id missing.");
            
		$url = 'responses/'.$id;

		$response = $this->get($url);
		return json_decode($response, true);
	}
    
	/*
	 * Delete a response
     * returns true or false
     * fonenode.com/docs#responses-delete
	*/
	public function delete_response($id) {
        if (!$id)
			throw new MissingParameterException("Response id missing.");
            
		$url = 'responses/'.$id;

		$response = $this->delete($url);
		$json = json_decode($response, true);
        if ($json['id']) {
            return true;
        }
        else {
            $this->_error = $json['error'];
            return false;
        }
	}
    
	/*
	 * Calls
     * fonenode.com/docs#calls
	*/
    
	/*
	 * Quick call
	 * Make a quick call without creating a response
     * fonenode.com/docs#calls-quick
	*/
	public function quick_call($to, $text, $voice = null, $from = null) {
		$url = 'calls/quick';

        if (!$to)
			throw new MissingParameterException("To parameter missing.");
        if (!$text)
			throw new MissingParameterException("Call text/link missing.");

        $data = array(
                    'to' => $to,
                    'text' => $text
                );
        if ($voice)
            $data['voice'] = $voice;
        if ($from)
            $data['from'] = $from;
		$response = $this->post($url, $data);
		return json_decode($response, true);
	}
    
	/*
	 * Call
	 * Make calls to one or more numbers using a response already created.
     * fonenode.com/docs#calls-make
	*/
	public function call($to, $response_id, $voice = null, $from = null) {
		$url = 'calls';

        if (!$to)
			throw new MissingParameterException("To parameter missing.");
        if (!$response_id)
			throw new MissingParameterException("Response id missing.");

        $data = array(
                    'to' => $to,
                    'response_id' => $response_id
                );
        if ($from)
            $data['from'] = $from;
        if ($voice)
            $data['voice'] = $voice;
		$response = $this->post($url, $data);
		return json_decode($response, true);
	}
    
	/*
	 * List Calls
	 * List inbound and outbound calls via your account.
	 * $filter: picked, notpicked, called, notcalled, outbound or inbound
     * fonenode.com/docs#calls-list
	*/
	public function list_calls($limit = 20, $offset = 0, $filter = null) {
		$url = 'calls?';

		if ((int) $limit)
			$query['limit'] = $limit;
		if ((int) $offset)
			$query['offset'] = $offset;
		if ($filter)
			$query['filter'] = $filter;
            
        $url .=  http_build_query($query);
        
		$response = $this->get($url);
		return json_decode($response, true);
	}
	
	/*
	 * Get a call
     * fonenode.com/docs#calls-get
	*/
	public function get_call($id) {
        if (!$id)
			throw new MissingParameterException("Call id missing.");
            
		$url = 'calls/'.$id;

		$response = $this->get($url);
		return json_decode($response, true);
	}
    
	/*
	 * Billing
     * fonenode.com/docs#billing
	*/
    
	/*
	 * List billing reports
	 * $filter: numbers, outbound or inbound
     * fonenode.com/docs#bills-list
	*/
	public function list_bills($limit = 20, $offset = 0, $filter = null) {
		$url = 'billing?';

		if ((int) $limit)
			$query['limit'] = $limit;
		if ((int) $offset)
			$query['offset'] = $offset;
		if ($filter)
			$query['filter'] = $filter;
            
        $url .=  http_build_query($query);
        
		$response = $this->get($url);
		return json_decode($response, true);
	}
    
	/*
	 * Private methods
	 */
	
	private function get($url) {
		return $this->http($url);
	}
	
	private function post($url, $data) {
		return $this->http($url, $data);
	}
	
	private function delete($url) {
		// Instead of using curl's CURLOPT_CUSTOMREQUEST,
		// we use the get hack for now
		return $this->http($url.'?_method=delete');
	}
	
    /**
     * HTTP request handler
	 *
	 * $url: url to post or get
	 * $data: post data for POST
	 * @returns array of http status and response
     */
	private function http($url, $data = null) {
		// print_r($data); #debug
		
		$ch = curl_init();
		
		$callurl = $this->_api_root.$url;
		// echo $callurl; #debug
		
		curl_setopt($ch, CURLOPT_URL, $callurl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->_auth_id.':'.$this->_auth_secret);
		
		if (isset($data)) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		$response = curl_exec($ch);
		$this->_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		//echo $response; #debug
		return $response;
	}
}
?>