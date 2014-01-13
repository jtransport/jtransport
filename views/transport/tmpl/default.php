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

	var transport_method = '<?php echo $params->transport_method; ?>';
	var debug_step = '<?php echo $params->debug_step; ?>';
	var debug_transport = '<?php echo $params->debug_transport; ?>';

	$(function() {
		$('#error').css('display','none');
		$('#warning').css('display','none');
		$("#start-transport").attr('disabled',false);

		$("#start-transport").click(function() {
			$("#start-transport").attr('disabled', true);
			preTransportProcess();
		});

		preTransportProcess = function()
		{
			$.ajax({
				url:'index.php?option=com_jtransport&format=raw&task=ajax.preTransport',
				success:function(result){
					var object = JSON.decode(result);

					// User's config error
					if (object != null && object.number == 500)
					{
						$("#log").append(object.text);
					}
					else
					{
						$("#log").append("Pre transport progress: completed" + "<br/>");
						stepProcess();
					}
				}
			});
		}

		var stepNo = 0;
		var preStep = '';

		stepProcess = function()
		{
			$.ajax({
				url:'index.php?option=com_jtransport&format=raw&task=ajax.step',
				success:function(result){
					var object = JSON.decode(result);

					if (object.start == 0)
					{
						$("#one-step-progress").css("width", "0%");
						$("#one-step-text").html("");
						$("#log").append("Transport " + object.title + "<span id='" + object.name + "_status'>: 0/" + object.total + " items" + "<span/>");
						$("#log").append("<br/>");
					}

					if (object.total == 0)
					{
						if (object.name == object.laststep)
						{
							$("#all-steps-progress").css("width", "100%");
							$("#all-steps-text").html("100% Complete");
							alert('Transport Completed');
						}
						else
						{
							stepProcess();
						}
					}

					if (preStep != object.name)
					{
						var allStepsPercent = Math.round((stepNo * 100) / object.stepTotal);
						$("#all-steps-progress").css("width", allStepsPercent + "%");
						$("#all-steps-text").html(allStepsPercent + "% Complete");

						stepNo ++;
						preStep = object.name;
					}

					if (debug_step == 1)
					{
						$("#debug").append('<span class="icon-ok"></span><br/>' + result + '<br/><br/>');
					}

					if (transport_method == 'database')
					{
						if (object.total > 0)
						{
							transportProgress(object.name);
						}
					}
				}
			});
		}

		transportProgress = function(table)
		{
			$.ajax({
				url:'index.php?option=com_jtransport&format=raw&task=ajax.transport&table=' + table,
				success:function(result){
					var object = JSON.decode(result);

					// Update one step's progress bar status
					var oneStepPercent = Math.round((object.cid * 100) / object.total);
					$("#one-step-progress").css("width", oneStepPercent + "%");
					$("#one-step-text").html(oneStepPercent + "% Complete");

					// Write log status
					$("#" + object.name + "_status").html(": " + object.cid + "/" + object.total + " items");

					// End of one step transport
					if (object.cid == object.stop.toInt() + 1 || object.next == 1)
					{
						// This step is last step and end chunk of this step -> finish transport
						if (object.name == object.laststep && object.end == 1)
						{
							$("#all-steps-progress").css("width", "100%");
							$("#all-steps-text").html("100% Complete");
							alert("Transport completed");
						}
						else if (object.next == 1) // This step is not last step
						{
							stepProcess();
						}
					}
				}
			});
		}
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
						<div id="one-step-progress" class="bar progress-bar-one-step" role="progressbar" style="width: 0%">
							<span id="one-step-text" class="sr-only"></span>
						</div>
					</div>
					<div class="progress">
						<div id="all-steps-progress" class="bar progress-bar-all-steps" role="progressbar" style="width: 0%">
							<span id="all-steps-text" class="sr-only"></span>
						</div>
					</div>
					<div class="btn-group">
						<button id="start-transport" type="button" class="btn btn-large btn-success">Start Transport</button>
					</div>
				</div>
				<div id="log-area" class="span4">
					<h2>Log</h2>
					<div id="error" class="error"></div>
                    <div id="warning" class="warning"></div>
					<div id="log" class="log"></div>
				</div>
				<div id="debug-area" class="span8">
					<h2>Debug</h2>
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