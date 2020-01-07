@extends('cms.layout.master')
@section('title', 'Create Role')
@section('parentPageTitle', 'Role')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css')}}"/>
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-select/css/bootstrap-select.css')}}"/>
@stop
@section('content')

    <!-- Input -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12">

            <div class="card">
                <div class="body">
                    <div class="row">
                        <div class="col-sm-12">

                            @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <span class="alert-inner--text"><strong>Whoops!</strong> There were some problems with your input</span>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-sm-8 offset-sm-2">
                            <form action="{{ route('roles.store') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">     
                                    <strong>Name:</strong>                               
                                    <input id="Name" type="text" name="name" class="form-control" placeholder="Name">
                                </div>
                                <div class="form-group">     
                                    <strong>Guard Name:</strong>                               
                                    <select class="form-control" name="guard_name" id="guard_name">
                                        <option value="api" selected="">API</option>
                                        <option value="web">CMS</option>
                                    </select>
                                </div>
                                <div class="form-group api">
                                    <label class="form-control-label"><strong>Permissions API:</strong></label>
                                    @foreach($permissionsApi as $idx => $value)
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="opt-{{$idx}}"
                                            name="permission[]" value="{{ $value->name }}">
                                        <label class="custom-control-label" for="opt-{{$idx}}">{{ $value->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-group web">
                                    <label class="form-control-label"><strong>Permissions CMS:</strong></label>
                                    @foreach($permissionsWeb as $idx => $value)
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input type="checkbox" class="custom-control-input" id="opt-cms-{{$idx}}"
                                            name="permission[]" value="{{ $value->name }}">
                                        <label class="custom-control-label" for="opt-cms-{{$idx}}">{{ $value->name }}</label>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-raised btn-primary btn-round waves-effect float-right">Submit</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

@stop
@section('page-script')
<script src="{{asset('assets/plugins/momentjs/moment.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js')}}"></script>
<script src="{{asset('assets/js/pages/forms/basic-form-elements.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function(){

        $(".web").hide();

        $("#guard_name").on('change',function(){
            var val = $(this).val();
            switch(val){
                case 'api':
                    $(".api").show();
                    $(".web").hide();
                break;
                case 'web':
                    $(".web").show();
                    $(".api").hide();
                break;
            }
        });

    });
</script>
@stop