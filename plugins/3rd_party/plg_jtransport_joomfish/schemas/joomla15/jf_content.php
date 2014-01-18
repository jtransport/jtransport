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

require_once JPATH_PLUGINS . '/jtransport/jtransport_joomfish/inflector/inflector.php';

class JTransportJoomfishContent extends JTransport
{
    public function dataHook($rows)
    {
        $session = JFactory::getSession();

        // 
        $arrMovedTables = array('content', 'modules', 'categories', 'menu');

        // Do some custom post processing on the list.
        foreach ($rows as &$row)
        {
            $row = (array) $row;

            // 
            if (in_array($row['reference_table'], $arrMovedTables))
            {
                // The record will be inserted to new table
                $objRecord = new stdClass;

                if ($row['reference_table'] == 'content' && $row['reference_field'] == 'attribs')
                {
                    $arrAttribs = explode("\n", $row['value']);

                    $arrCreatedBy = explode('=', $arrAttribs[0]);
                    $objRecord->created_by = $arrCreatedBy[1];

                    $arrCreatedByAlias = explode('=', $arrAttribs[1]);
                    $objRecord->created_by_alias = $arrCreatedByAlias[1];

                    $arrAccess = explode('=', $arrAttribs[2]);
                    $objRecord->access = $arrAccess[1];

                    $arrCreated = explode('=', $arrAttribs[3]);
                    $objRecord->created = $arrCreated[1];

                    $arrPublishUp = explode('=', $arrAttribs[4]);
                    $objRecord->publish_up = $arrPublishUp[1];

                    $arrPublishDown = explode('=', $arrAttribs[5]);
                    $objRecord->publish_down = $arrPublishDown[1];

                    $objAttribs = new stdClass;

                    for($i = 6; $i < 23; $i++)
                    {
                        if (isset($arrAttribs[$i]))
                        {
                            $arrAttribsEle = explode('=', $arrAttribs[$i]);
                            $objAttribs->$arrAttribsEle[0] = $arrAttribsEle[1];
                        }
                    }

                    $objRecord->attribs = json_encode($objAttribs);
                }
                else if ($row['reference_table'] == 'modules' && $row['reference_field'] == 'params')
                {
                    $arrParams = explode("\n", $row['value']);

                    $objParams = new stdClass;

                    for($i = 0; $i < 11; $i++)
                    {
                        if (isset($arrParams[$i]))
                        {
                            $arrParamsEle = explode('=', $arrParams[$i]);
                            $objParams->$arrParamsEle[0] = $arrParamsEle[1];
                        }
                    }

                    $objRecord->params = json_encode($objParams);
                }
                else if ($row['reference_table'] == 'menu' && $row['reference_field'] == 'name')
                {
                    $objRecord->title = $row['value'];
                }
                else
                {
                    $objRecord->$row['reference_field'] = $row['value'];
                    $objRecord->access = 1;
                }

                $languageId = $row['language_id'];
                $referenceId = $row['reference_id'];
                $arrMovedRecords = (array) $session->get('arrMovedRecords', null, 'jtransport_joomfish');
                
                // 
                $tableId = $this->getInsertedId($arrMovedRecords, $languageId, $referenceId);

                // The record haven't been inserted, so will insert record
                if ($tableId == 0)
                {
                    // Mark first record which inserted into new table
                    // It will be saved into session
                    $record = new stdClass;
                    $record->languageId = $row['language_id'];
                    $record->referenceId = $row['reference_id'];
                    $record->referenceTable = $row['reference_table'];

                    // Get language code to save it to new table
                    $language = $this->getLanguageCode($row['language_id']);

                    $objRecord->language = $language;

                    // The record contens temporary infomations which got from moved data table 
                    $objTmpRecord = $this->getRecord($row['reference_table'], $row['reference_id']);

                    $tableName = \Doctrine\Common\Inflector\Inflector::singularize($row['reference_table']);

                    // Object handles moved data table
                    $objTable = JTable::getInstance($tableName, 'JTable', array('dbo' => $this->_db));

                    if ($row['reference_table'] == 'content')
                    {
                        if ($objTmpRecord != null)
                        {
                            $objRecord->state = $objTmpRecord->state;
                            $objRecord->sectionid = $objTmpRecord->sectionid;
                            $objRecord->mask = $objTmpRecord->mask;
                            $objRecord->catid = $objTmpRecord->catid;
                        }

                        $objRecord->modified = $row['modified'];
                        $objRecord->modified_by = $row['modified_by'];
                    }
                    elseif ($row['reference_table'] == 'modules')
                    {
                        if ($objTmpRecord != null)
                        {
                            $objRecord->note = $objTmpRecord->note;
                            $objRecord->content = $objTmpRecord->content;
                        }
                    }
                    elseif ($row['reference_table'] == 'categories')
                    {
                        if ($objTmpRecord != null)
                        {
                            $objRecord->path = $objTmpRecord->path;
                            $objRecord->extension = $objTmpRecord->extension;
                            $objRecord->params = $objTmpRecord->params;
                            $objRecord->created_user_id = $objTmpRecord->created_user_id;
                            $objRecord->created_time = $objTmpRecord->created_time;

                            $objTable->setLocation($objTmpRecord->id);
                        }
                        
                        $objRecord->modified_time = $row['modified'];
                        $objRecord->modified_user_id = $row['modified_by'];
                    }
                    elseif ($row['reference_table'] == 'menu')
                    {
                        if ($objTmpRecord != null)
                        {
                            $objRecord->menutype = $objTmpRecord->menutype;
                            $objRecord->type = $objTmpRecord->type;
                            $objRecord->component_id = $objTmpRecord->component_id;

                            $objTable->setLocation($objTmpRecord->id);
                        }
                    }

                    $objRecord->published = $row['published'];

                    // Save record to new table
                    $arrRecord = (array) $objRecord;

                    $objTable->bind($arrRecord);

                    $objTable->store();

                    // Get id of record recently saved
                    $record->tableId = $this->getMaxId($row['reference_table']);
        
                    // Save marked record to session            
                    $arrMovedRecords[] = $record;
                    $session->set('arrMovedRecords', $arrMovedRecords, 'jtransport_joomfish');

                    // Move data to translationmap talbe
                    $translationmapRecord = new stdClass;
                    $translationmapRecord->language = $language;
                    $translationmapRecord->reference_id = $row['reference_id'];
                    $translationmapRecord->translation_id = $record->tableId;
                    $translationmapRecord->reference_table = $row['reference_table'];

                    $this->_db->insertObject('#__jf_translationmap', $translationmapRecord);
                }
                else
                {
                    $objRecord->id = $tableId;

                    $this->_db->updateObject('#__' . $row['reference_table'], $objRecord, 'id');
                }
            }

	        // Remove fields not exist in destination table
	        // $this->_removeUnusedFields($row);
        }

        return $rows;
    }

    public function getRecord($table, $id)
    {
        $db = $this->_db;

        $query = $db->getQuery(true);

        $query->select('*')
                ->from('#__' . $table)
                ->where('id = ' . $id);

        $db->setQuery($query);

        $objRecord = $db->loadObject();

        return $objRecord;
    }

    public function getLanguageCode($languageId)
    {
        $db = $this->_db;

        $query = $db->getQuery(true);

        $query->select('lang_code')
                ->from('#__languages')
                ->where('lang_id = ' . $languageId);

        $db->setQuery($query);

        $lang_code = $db->loadResult();

        return $lang_code;
    }

    public function getInsertedId($arrMovedRecords, $languageId, $referenceId)
    {
        foreach ($arrMovedRecords as $record)
        {
            if ( ($record->languageId == $languageId) && ($record->referenceId == $referenceId) )
            {
                return $record->tableId;
            }
        }

        return 0;
    }

    public function getMaxId($table)
    {
        $db = $this->_db;

        $query = $db->getQuery(true);

        $query->select('MAX(id)')
                ->from('#__' . $table);

        $db->setQuery($query);

        $maxId = $db->loadResult();

        return $maxId;
    }

    public function afterHook($rows)
    {
        $session = JFactory::getSession();
        $session->set('arrMovedRecords', null, 'jtransport_joomfish');

        $db = $this->_db;
 
        $query = $db->getQuery(true);
         
        $query->delete('#__jf_content')
                ->where('reference_table IN ("content", "modules", "categories", "menu")');

        $db->setQuery($query);
 
        $db->query();

        return parent::afterHook($rows);
    }
}
?>