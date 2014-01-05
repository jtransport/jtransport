<?php
/**
 * JTransport
 *
 * @author  vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Note: this view is intended only to be opened in a popup
 *
 * @package     Joomla.Administrator
 * @subpackage  com_transport
 * @since       1.5
 */
class JTransportControllerTransport extends JControllerLegacy
{
	/**
	 * Cancel operation
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}
}
