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

// No direct access.
defined('_JEXEC') or die;

// BOOTSTRAP

JHtml::_('script', 'com_jtransport/jquery.js', false, true);
JHtml::_('script', 'com_jtransport/bootstrap.js', false, true);
JHtml::_('stylesheet', 'com_jtransport/bootstrap.css', false, true);

JHtml::_('script', 'com_jtransport/dwProgressBar.js', false, true);
JHtml::_('script', 'com_jtransport/jtransport.js', false, true);
JHtml::_('script', 'com_jtransport/requestmultiple.js', false, true);
JHtml::_('stylesheet', 'com_jtransport/jtransport.css', false, true);

JLoader::import('helpers.sidebar', JPATH_COMPONENT_ADMINISTRATOR);

// Get params
$params	= $this->params;

?>

<script type="text/javascript">

window.addEvent('domready', function() {

	/* Init jtransport */
	var JTransport = new jtransport({
		method: '<?php echo $params->transport_method ? $params->transport_method : 0; ?>',
		debug_step: <?php echo $params->debug_step ? $params->debug_step : 0; ?>,
		debug_transport: <?php echo $params->debug_transport ? $params->debug_transport : 0; ?>
	});

});

Joomla.submitbutton = function(task)
{
	Joomla.submitform(task, document.getElementById('item-form'));
}

</script>

<form action="<?php echo JRoute::_('index.php?option=com_jtransport'); ?>" method="post" id="item-form" name="adminForm" class="form-validate">

<div class="row-fluid">
	<div class="span12">
		<?php echo JTransportHelperSideBar::getSideNavigation(); ?>
		<div id="jtransport-panel-right" class="span10">
		<table width="100%">
			<tbody>
				<tr>
					<td width="100%" valign="top" align="center">

						<div id="error" class="error"></div>

						<div id="warning" class="warning">
							<?php echo JText::_('COM_JTRANSPORT_WARNING_SLOW'); ?>
						</div>

						<div id="update">
							<br /><img src="components/com_redmigrator/images/update.png" align="middle" border="0"/><br />
							<h2><?php echo JText::_('START MIGRATE'); ?></h2><br />
						</div>

						<div id="core_checks">
							<p class="text"><?php echo JText::_('Core checking and cleaning...'); ?></p>
							<div id="pb0"></div>
							<div><small><i><span id="checkstatus"><?php echo JText::_('Initialize...'); ?></span></i></small></div>
						</div>

						<div id="ext_init">
							<p class="text"><?php echo JText::_('Initialize extensions...'); ?></p>
							<div id="pb7"></div>
							<div><small><i><span id="ext_status"><?php echo JText::_('Initialize extensions...'); ?></span></i></small></div>
						</div>

						<div id="migration">
							<p class="text"><?php echo JText::_('Migrating progress...'); ?></p>
							<div id="pb4"></div>
							<div><small><i><span id="migrate_status"><?php echo JText::_('Initialize...'); ?></span></i></small></div>
							<div id="counter">
								<i><small><b><span id="currItem">0</span></b> items /
								<b><span id="totalItems">0</span></b> items</small></i>
							</div>
						</div>

						<div id="files">
							<p class="text"><?php echo JText::_('Copying images/media files...'); ?></p>
							<div id="pb5"></div>
							<div><small><i><span id="files_status"><?php echo JText::_('Initialize...'); ?></span></i></small></div>
							<div id="files_counter">
								<i><small><b><span id="files_currItem">0</span></b> items /
								<b><span id="files_totalItems">0</span></b> items</small></i>
							</div>
						</div>

						<div id="templates">
							<p class="text"><?php echo JText::_('Copying templates...'); ?></p>
							<div id="pb6"></div>
						</div>

						<div id="done">
							<h2 class="done"><?php echo JText::_('Transport Successful!'); ?></h2>
						</div>

						<div id="info">
							<div id="info_version">
								<i>
									<?php echo JText::_('JTransport'); ?>
								</i>
								<?php echo JText::_('Version') . ' <b>1.0.0</b>'; ?>
							</div>
							<div id="info_thanks">
								<p>
									<?php echo JText::_('Developed by'); ?> <i><a href="http://www.redcomponent.com/">redCOMPONENT &#169;</a></i>  Copyright 2005-2013<br />
									Licensed as <a href="http://www.gnu.org/licenses/old-licenses/gpl-2.0.html"><i>GNU General Public License v2</i></a><br />
								</p>
								<h3>
									<a href="http://wiki.redcomponent.com/index.php?title=RedMIGRATOR:Table_of_Contents">Wiki</a><br />
								</h3>
							</div>
						</div>

						<div>
							<div id="debug"></div>
						</div>

					</td>
				</tr>
			</tbody>
		</table>

		</div>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
		</div>
	</div>
</div>
</form>