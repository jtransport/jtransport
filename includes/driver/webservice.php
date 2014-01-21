<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * JTransport webservice utility class
 *
 * @since  1.0.0
 */
class JTransportDriverWebservice extends JTransportDriver
{
	/**
	 * Constructor
	 *
	 * @param   JTransportStep  $step  Current step
	 */
	function __construct(JTransportStep $step = null)
	{
		parent::__construct($step);
	}

	/**
	 * Get the raw data for this part of the upgrade.
	 *
	 * @return	array	Returns a reference to the source data array.
	 *
	 * @throws	Exception
	 */
	public function getAuthData()
	{
		$data = array();

		// Setting the headers for REST
		$restful_username = $this->params->webservice_username;
		$restful_password = $this->params->webservice_password;
		$restful_key = $this->params->webservice_security_key;

		// Setting the headers for REST
		$str = $restful_username . ":" . $restful_password;
		$data['Authorization'] = base64_encode($str);

		// Encoding user
		$user_encode = $restful_username . ":" . $restful_key;
		$data['AUTH_USER'] = base64_encode($user_encode);

		// Sending by other way, some servers not allow AUTH_ values
		$data['USER'] = base64_encode($user_encode);

		// Encoding password
		$pw_encode = $restful_password . ":" . $restful_key;
		$data['AUTH_PW'] = base64_encode($pw_encode);

		// Sending by other way, some servers not allow AUTH_ values
		$data['PW'] = base64_encode($pw_encode);

		// Encoding key
		$key_encode = $restful_key . ":" . $restful_key;
		$data['KEY'] = base64_encode($key_encode);

		return $data;
	}

	/**
	 * Get source data
	 *
	 * @return array|null
	 */
	public function getSourceData()
	{
		return (array) $this->_request('row');
	}

	/**
	 * Get total rows of source table
	 *
	 * @return int
	 */
	public function getTotal()
	{
		return (int) $this->_request('total');
	}

	/**
	 * Receive data from source site through webservice
	 *
	 * @param   string  $task  Total or row
	 *
	 * @return int|string
	 *
	 * @throws Exception
	 */
	protected function _request($task = 'total')
	{
		$http = JHttpFactory::getHttp();

		$data = $this->getAuthData();

		$data['table'] = $this->_step->source;
		$data['task'] = $task;

		if ($task == 'row')
		{
			$data['start'] = $this->_step->cid;
			$data['limit'] = 1;
			$data['order'] = $this->_step->tbl_key;
		}

		$request = $http->get($this->params->webservice_hostname . '/index.php', $data);

		$code = $request->code;

		if ($code == 500)
		{
			throw new Exception('COM_JTRANSPORT_JTRANSPORT_ERROR_REQUEST');
		}
		else
		{
			if ($code == 200 || $code == 301)
			{
				return json_decode($request->body);
			}
		}

		return $code;
	}
}
