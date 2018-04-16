<section class="content-header">
    <h1>
        {{$text}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{$text}}</li>
    </ol>
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible alert-margin">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session()->get('error') }}
    </div>
    @endif
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible alert-margin">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session()->get('message') }}
    </div>
    @endif
</section>
