<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JLoader::import('joomla.application.component.modeladmin');

/**
 * JTransportModelConfig
 *
 * @package     Joomla.Administrator
 * @subpackage  com_jtransport
 * @since       1.6
 */
class JTransportModelTransport extends JModelLegacy
{
    /**
     * Method to get a single record.
     *
     * @param   integer    The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     */
    public function getParams()
    {
	    $params = JComponentHelper::getParams('com_jtransport');

        return $params->toObject();
    }
}
