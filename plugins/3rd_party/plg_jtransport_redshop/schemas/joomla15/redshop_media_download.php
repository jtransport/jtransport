<?php
/**
 * @package     RedMIGRATOR.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 *  redMIGRATOR is based on JUpgradePRO made by Matias Aguirre
 */

class JTransportRedshopMediaDownload extends JTransport
{
	public function dataHook($rows)
	{
		// Do some custom post processing on the list.
		foreach ($rows as &$row)
		{
			$row = (array) $row;

			$arrName = explode("/", $row['name']);

			$filename = $arrName[count($arrName) - 1];

			$row['name'] = JPATH_ROOT . '/components/com_redshop/assets/download/product/' . $filename;
		}

		return $rows;
	}
}
?>