<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// BOOTSTRAP

JHtml::_('script', 'com_jtransport/jquery.js', false, true);
JHtml::_('script', 'com_jtransport/bootstrap.js', false, true);
JHtml::_('stylesheet', 'com_jtransport/bootstrap.css', false, true);
JHtml::_('stylesheet', 'com_jtransport/jtransport.css', false, true);

JLoader::import('helpers.sidebar', JPATH_COMPONENT_ADMINISTRATOR);

// TABS
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		Joomla.submitform(task, document.getElementById('item-form'));
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_jtransport'); ?>" method="post" id="item-form" name="adminForm">

<div class="row-fluid">
	<div class="span12">
		<?php echo JTransportHelperSideBar::getSideNavigation(); ?>

		<div class="span10 form-horizontal">
		<ul class="nav nav-tabs">
		    <li class="active"><a href="#global" data-toggle="tab"><?php echo JText::_('COM_JTRANSPORT_TAB_GLOBAL');?></a></li>

			<li><a href="#webservice" data-toggle="tab"><?php echo JText::_('COM_JTRANSPORT_TAB_WEBSERVICE');?></a></li>

			<li><a href="#database" data-toggle="tab"><?php echo JText::_('COM_JTRANSPORT_TAB_DATABASE');?></a></li>

		    <li><a href="#coretransport" data-toggle="tab"><?php echo JText::_('COM_JTRANSPORT_TAB_CORETRANSPORT');?></a></li>

			<li><a href="#advance" data-toggle="tab"><?php echo JText::_('COM_JTRANSPORT_TAB_ADVANCE');?></a></li>

			<li><a href="#debug" data-toggle="tab"><?php echo JText::_('COM_JTRANSPORT_TAB_DEBUG');?></a></li>
		</ul>

		<div class="tab-content">
		<!-- Begin Tabs -->
		<div class="tab-pane active" id="global">
		    <fieldset class="adminform">
		        <div class="control-group">
		            <?php echo $this->form->getLabel('transport_method'); ?>
                    <div class="controls">
		                <?php echo $this->form->getInput('transport_method'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <?php echo $this->form->getLabel('core_version'); ?>
                    <div class="controls">
			            <?php echo $this->form->getInput('core_version'); ?>
                    </div>
		        </div>
		    </fieldset>
		</div>

		<div class="tab-pane" id="webservice">
			<fieldset class="adminform">
                <div class="control-group">
                    <?php echo $this->form->getLabel('webservice_hostname'); ?>
                    <div class="controls">
                        <?php echo $this->form->getInput('webservice_hostname'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('webservice_username'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('webservice_username'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('webservice_password'); ?>
                    <div class="controls">
					<?php echo $this->form->getInput('webservice_password'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('webservice_security_key'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('webservice_security_key'); ?>
                    </div>
				</div>
			</fieldset>
		</div>

		<div class="tab-pane" id="database">
			<fieldset class="adminform">
				<div class="control-group">
					<?php echo $this->form->getLabel('database_driver'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('database_driver'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('database_hostname'); ?>
                    <div class="controls">
                        <?php echo $this->form->getInput('database_hostname'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('database_username'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('database_username'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('database_password'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('database_password'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('database_name'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('database_name'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('table_prefix'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('table_prefix'); ?>
                    </div>
				</div>
				<div class="control-group">
					<?php echo $this->form->getLabel('chunk_limit'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('chunk_limit'); ?>
					</div>
				</div>
			</fieldset>
		</div>

		<div class="tab-pane" id="coretransport">
			<fieldset class="adminform">
				<div class="control-group">
					<?php echo $this->form->getLabel('transport_users'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_users'); ?>
                    </div>
                </div>
				<div class="control-group">
					<?php echo $this->form->getLabel('transport_usergroups'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('transport_usergroups'); ?>
					</div>
				</div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_categories'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_categories'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_content'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_content'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_content_frontpage'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_content_frontpage'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_menu'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_menu'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_menu_types'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_menu_types'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_modules'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_modules'); ?>
                    </div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_modules_menu'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_modules_menu'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_banners'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_banners'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_banner_clients'); ?>
                    <div class="controls">
					<?php echo $this->form->getInput('transport_banner_clients'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_banner_tracks'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_banner_tracks'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_contacts'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_contacts'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_newsfeeds'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_newsfeeds'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('transport_weblinks'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('transport_weblinks'); ?>
					</div>
				</div>
			</fieldset>
		</div>

		<div class="tab-pane" id="advance">
			<fieldset class="adminform">
				<div class="control-group">
					<?php echo $this->form->getLabel('remove_target_users'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_users'); ?>
					</div>
                </div>
				<div class="control-group">
					<?php echo $this->form->getLabel('remove_target_usergroups'); ?>
					<div class="controls">
						<?php echo $this->form->getInput('remove_target_usergroups'); ?>
					</div>
				</div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_categories'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_categories'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_content'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_content'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_content_frontpage'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_content_frontpage'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_menu'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_menu'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_menu_types'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_menu_types'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_modules'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_modules'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_modules_menu'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_modules_menu'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_banners'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_banners'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_banner_clients'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_banner_clients'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_banner_tracks'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_banner_tracks'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_contacts'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_contacts'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_newsfeeds'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_newsfeeds'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('remove_target_weblinks'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('remove_target_weblinks'); ?>
                    </div>
				</div>
			</fieldset>
		</div>

		<div class="tab-pane" id="debug">
			<fieldset class="adminform">
				<div class="control-group">
					<?php echo $this->form->getLabel('php_error_report'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('php_error_report'); ?>
					</div>
                </div>
                <div class="control-group">
					<?php echo $this->form->getLabel('debug_step'); ?>
                    <div class="controls">
					    <?php echo $this->form->getInput('debug_step'); ?>
					</div>
                </div>
                <!--<div class="control-group">
					<?php /*echo $this->form->getLabel('debug_transport'); */?>
                    <div class="controls">
					    <?php /*echo $this->form->getInput('debug_transport'); */?>
                    </div>
				</div>-->
			</fieldset>
		</div>
		<!-- End Tabs -->
		</div>
			<input type="hidden" name="task" value=""/>
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</div>
</div>
</form>


