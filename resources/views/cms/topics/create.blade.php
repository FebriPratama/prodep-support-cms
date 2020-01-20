@extends('cms.layout.master')
@section('title', 'Create Topic')
@section('parentPageTitle', 'Topic')
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
                            <form action="{{ route('topics.store') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">     
                                    <strong>Name:</strong>                               
                                    <input id="Name" type="text" name="name" class="form-control" placeholder="Name">
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