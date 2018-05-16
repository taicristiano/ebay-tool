@if(session('message'))
<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	{{ session('message') }}
</div>
@endif
@if($errors->message->first())
<div class="alert alert-danger">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	{{ $errors->message->first() }}
</div>
@endif