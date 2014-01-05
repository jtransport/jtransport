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
JHtml::_('script', 'com_jtransport/bootstrap-progressbar.js', false, true);
JHtml::_('script', 'com_jtransport/knockout.min.js', false, true);
JHtml::_('stylesheet', 'com_jtransport/bootstrap.min.css', false, true);
JHtml::_('stylesheet', 'com_jtransport/jtransport.css', false, true);

JLoader::import('helpers.sidebar', JPATH_COMPONENT_ADMINISTRATOR);

// Get params
$params	= $this->params;

?>

<script type="text/javascript">
    var transport_method = '<?php echo $params->transport_method; ?>';

	$(function () {
        $('#error').css('display','none');
        $('#warning').css('display','none');

		$('#all-steps-progress').progressbar({
			warningMarker: 100,
			dangerMarker: 100,
			maximum: 100,
			step: 1
		});

		$('#all-steps-progress').on("positionChanged", function (e) {
			viewModel.percent(e.percent);
		});

		ko.applyBindings(viewModel);
	});

	var viewModel = {
		percent: ko.observable(0),
		step: ko.observable(1),
		maximum: ko.observable(100),
		position: ko.observable(80),
		interval: undefined,
		isRunning: ko.observable(false),

		stepIt: function () {
			$('#all-steps-progress').progressbar('stepIt');
		},

		reset: function () {
			$('#all-steps-progress').progressbar('reset');
		},

		start: function () {

            if (this.isRunning())
            {
                return;
            }

			this.reset();

            if (transport_method == 'webservice')
            {
                $('#error').css('display','block');
                preTransport();
            }
            else
            {
                this.isRunning(true);
                var self = this;
                this.interval = setInterval(function () {
                    $('#all-steps-progress').progressbar('stepIt');
                    if (self.percent() >= 100) {
                        clearInterval(self.interval);
                        self.interval = undefined;
                        self.isRunning(false);
                    }
                }, 250);
            }
		}
	};

	viewModel.formattedPercent = ko.computed(function () {
		return this.percent() + '%';
	}, viewModel);

    preTransport = function()
    {
        alert('xxx');
    }

	/*viewModel.maximum.subscribe(function (newValue) {
		$('#all-steps-progress').progressbar('setMaximum', newValue);
	}, viewModel);

	viewModel.position.subscribe(function (newValue) {
		$('#all-steps-progress').progressbar('setPosition', newValue);
	}, viewModel);

	viewModel.step.subscribe(function (newValue) {
		$('#all-steps-progress').progressbar('setStep', newValue);
	}, viewModel);*/

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
					<div>
						<div id="all-steps-progress"></div>
						<span class="pull-right badge" data-bind="text: formattedPercent()">0%</span>
						<div class="pull-left">
							<input type="button" data-bind="click: start, enable: !isRunning()" value="Start Transport" class="btn btn-primary">
						</div>
					</div>
				</div>
				<div id="debug-info">
					<div id="error" class="error"></div>
                    <div id="warning" class="warning"></div>
				</div>
			</div>
			<div>
				<input type="hidden" name="task" value=""/>
				<?php echo JHtml::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>