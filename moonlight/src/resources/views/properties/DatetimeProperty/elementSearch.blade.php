<script type="text/javascript">
$(function() {
	$('#{{ $name }}_from').calendar({
		triggerElement: '#{{ $name }}_from_show',
		dateFormat: '%Y-%m-%d',
		showHandler: function() { $('#{{ $name }}_from').focus(); }
	});
	$('#{{ $name }}_to').calendar({
		triggerElement: '#{{ $name }}_to_show',
		dateFormat: '%Y-%m-%d',
		showHandler: function() { $('#{{ $name }}_to').focus(); }
	});
});
</script>
<span switch="true" name="{{ $name }}" class="dashed hand" style="color: magenta;">{{ $title }}</span>:<br>
<div id="{{ $name }}_block" style="display: {{ $from || $to ? 'block' : 'none' }};">
от <input type="text" class="prop-date" id="{{ $name }}_from" name="{{ $name }}_from" value="{{ $from ? $from->format('Y-m-d') : null }}"{{ $from || $to ? '' : ' disabled="disabled"' }}> <span id="{{ $name }}_from_show" class="hand"><img src="{{ asset('packages/lemon-tree/admin/img/calendar.gif') }}" alt="" /></span>
до <input type="text" class="prop-date" id="{{ $name }}_to" name="{{ $name }}_to" value="{{ $to ? $to->format('Y-m-d') : null }}"{{ $from || $to ? '' : ' disabled="disabled"' }}> <span id="{{ $name }}_to_show" class="hand"><img src="{{ asset('packages/lemon-tree/admin/img/calendar.gif') }}" alt="" /></span>
</div>