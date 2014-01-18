<?php
/**
 * @package     JTransport.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * 
 *  JTransport is based on JUpgradePRO made by Matias Aguirre
 */

class JTransportKunenaMessage extends JTransport
{
    public function dataHook($rows)
    {
        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);

            if ($row['parent'] == 0)
            {
                $this->insertIntoTopic($row);
            }
        }

        return $rows;
    }

    public function insertIntoTopic($row)
    {
        $query = $this->_db->getQuery(true);

        $query->clear();

        $query->insert('#__kunena_topics')
                ->set('category_id = ' . $row['catid'])
                ->set('subject = "' . $row['subject'] . '"')
                ->set('locked = ' . $row['locked'])
                ->set('hold = ' . $row['hold'])
                ->set('ordering = ' . $row['ordering'])
                ->set('hits = ' . $row['hits'])
                ->set('moved_id = ' . $row['moved'])
                ->set('first_post_id = ' . $row['id'])
                ->set('first_post_time = ' . $row['time'])
                ->set('first_post_userid = ' . $row['userid'])
                ->set('last_post_id = ' . $row['id'])
                ->set('last_post_time = ' . $row['time'])
                ->set('last_post_userid = ' . $row['userid']);

        $this->_db->setQuery($query);

        $this->_db->query();
    }
}
?>