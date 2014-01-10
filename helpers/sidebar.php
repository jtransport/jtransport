<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class JTransportHelperSideBar
{
    public static $extention = 'com_jtransport';

    /**
     * Display the side navigation bar, ACL aware
     *
     * @return  string the html representation of side navigation
     */
    public static function getSideNavigation()
    {
        $input = JFactory::getApplication()->input;
        $viewName = $input->get('view', '', 'cmd');
        $disabled = $input->get('disablesidebar', '0', 'int');
        $link = 'index.php?option=com_jtransport';

        if ($disabled) return;

        $menuStructure['JTRANSPORT'] = array(
            0 => array( 'COM_JTRANSPORT_CONFIG', '&view=config' ),
            1 => array( 'COM_JTRANSPORT_TRANSPORT', '&view=transport'),
        );

        $html = '';
        $html .= '<div id="jtransport-panel-left" class="span2">';
        $html .= '<ul id="jtransport-sidebar-navigation">';

        $iconMap = array(
            'master' => 'icon-home',
        );

        foreach ($menuStructure as $menuName => $menuDetails)
        {
            $html .= '<li class="jtransport-toggle" id="jtransport-sn_' . strtolower(substr($menuName, 11)) . '">
			            <a class="jtransport-indicator">Open</a>
			            <a class="jtransport-title"><i class="' . $iconMap[strtolower(substr($menuName, 11))] . '"></i> ' . JText::_($menuName) . '</a>';
            $html .= '<ul>';
            foreach ($menuDetails as $menu)
            {
                if ((substr($menu[1], 1, 4) == 'view'))
                {
                    $html .= '<li class="' . (substr($menu[1], 6) == $viewName ? 'active' : '') . '">';
                }
                else
                {
                    $html .= '<li class="">';
                }
                $html .= '<a id="' . strtolower($menu[0]) . '" href="' . JRoute::_($link.$menu[1]) . '">' . JText::_($menu[0]) . '</a></li>';
            }
            $html .= '</ul>';
            $html .= '</li>';
        }

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }
}
