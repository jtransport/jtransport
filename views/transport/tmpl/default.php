<?php
/**
 * JTransport
 *
 * @author vdkhai
 */

// No direct access.
defined('_JEXEC') or die;

// BOOTSTRAP

JHtml::_('script', 'com_jtransport/jquery.min.js', false, true);
JHtml::_('script', 'com_jtransport/bootstrap.min.js', false, true);
JHtml::_('stylesheet', 'com_jtransport/bootstrap.min.css', false, true);
JHtml::_('stylesheet', 'com_jtransport/jtransport.css', false, true);

JLoader::import('helpers.sidebar', JPATH_COMPONENT_ADMINISTRATOR);

// Get params
$params	= $this->params;

?>

<script type="text/javascript">

	$(function() {
		$('#error').css('display','none');
		$('#warning').css('display','none');

		$("#start-transport").click(function() {
			$.ajax({
				url:'index.php?option=com_jtransport&format=raw&task=ajax.preTransport',
				success:function(result){

					var object = JSON.decode(result);

					if (object.number == 500)
					{
						$("#debug").html(object.text);
					}

					$("#one-step-progress").css("width", "10%");
					$("#one-step-text").html("10% Complete");
				}
			});
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
				<div id="transport-progress">
					<h2>Transport progress</h2>
					<div class="progress">
						<div id="one-step-progress" class="bar progress-bar-one-step" role="progressbar" style="width: 40%">
							<span id="one-step-text" class="sr-only">40% Complete</span>
						</div>
					</div>
					<div class="progress">
						<div id="all-steps-progress" class="bar progress-bar-all-steps" role="progressbar" style="width: 20%">
							<span id="all-steps-text" class="sr-only">20% Complete</span>
						</div>
					</div>
					<div class="btn-group">
						<button id="start-transport" type="button" class="btn btn-large btn-success">Start Transport</button>
					</div>
				</div>
				<div id="debug-info">
					<div id="error" class="error"></div>
                    <div id="warning" class="warning"></div>
					<div id="debug" class="debug"></div>
				</div>
			</div>
			<div>
				<input type="hidden" name="task" value=""/>
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>