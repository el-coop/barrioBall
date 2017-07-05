@if(Session::has('alert'))
    <script>
		swal({
			title: 'Success',
			text: '{{ Session::get('alert') }}',
			type: 'success',
			timer: 2000
		});
    </script>
@elseif(count($errors) > 0)
    <script>
		swal({
			title: 'Error',
			text: '{{ $errors->first() }}',
			type: 'error',
			timer: 2000
		});
    </script>
@elseif(Session::has('error'))
    <script>
		swal({
			title: 'Error',
			text: '{{ Session::get('error') }}',
			type: 'error',
			timer: 2000
		});
    </script>
@endif