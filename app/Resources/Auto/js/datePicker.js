start = moment();
start = moment(Math.ceil((+start) / ROUNDING) * ROUNDING);
jQuery(document).ready(function() {
    $('#time_input').datetimepicker({
        format: 'YYYY-MM-DD',
        stepping: 1
    });
});