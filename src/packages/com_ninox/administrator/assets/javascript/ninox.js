/*
 * @license MIT License (https://github.com/NecatiMeral/joomla-ninox/blob/main/LICENSE)
 * @author  Necati Meral (you@you.you)
 * @url     https://github.com/NecatiMeral/joomla-ninox
 */

// https://stackoverflow.com/a/18234317
String.prototype.formatUnicorn = String.prototype.formatUnicorn ||
function () {
    "use strict";
    var str = this.toString();
    if (arguments.length) {
        var t = typeof arguments[0];
        var key;
        var args = ("string" === t || "number" === t) ?
            Array.prototype.slice.call(arguments)
            : arguments[0];

        for (key in args) {
            str = str.replace(new RegExp("\\{" + key + "\\}", "gi"), args[key]);
        }
    }

    return str;
};

jQuery(document).ready(function($) {

	$('#export .btn, #export label').click(function(event) {
		if ($(this).hasClass('disabled')) {
			event.stopImmediatePropagation();
			event.stopPropagation();
			return false;
		}
	});

});

function beginExport(btn) {
	var $ = jQuery, btn = $(btn);
	if (btn.hasClass('disabled')) {
        return;
    }

	$('#export .btn').addClass('disabled');
	$('#export input[type=text]').addClass('disabled').attr('readonly', true);
	$('#export .progress').addClass('progress-striped active');

	processExport($);
}

function processExport($) {
	$.ajax({
		url: ninox.livesite+'administrator/index.php?option=com_ninox&view=configuration&task=export&format=raw',
		type: 'post',
		dataType: 'json',
		data: {
			include_blocked: $('input[name="include_blocked"]:checked').val(),
			include_unconfirmed: $('input[name="include_unconfirmed"]:checked').val(),
			registered_after: $('#registered_after').val(),
			last_login_after: $('#last_login_after').val()
		},
		success: function(data) {
			$('#export .progress .bar').width(data.exported_percent+'%');
			if (data.error) {
				$('#export .alert').addClass('alert-error').removeClass('hidden').html(data.error);
				stopExport($)
				return;
			}
			else if (data.completed) {
				$('#export .alert')
                    .addClass('alert-info')
                    .removeClass('hidden')
                    .html(ninox.succeedTemplate.formatUnicorn(data.exported_total));
				stopExport($)
				return;
			}
			else
				setTimeout(function() { processExport($); }, 1200);
		}
	});
}

function stopExport($) {
	$('#export .btn').removeClass('disabled');
	$('#export input[type=text]').removeClass('disabled').attr('readonly', false);
	$('#export .progress').removeClass('progress-striped active');
}
