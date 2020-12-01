@extends('cms.layout.master')
@section('title', 'Connect FaceBook')
@section('parentPageTitle', 'User')
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
                    <div class="col-sm-12">

                        <div class="alert alert-info">
                            <p>Untuk mengaktifkan fitur, Silahkan login menggunakan akun facebook</p>
                            <ul>
                                <li>Pilih Akun instagram yang akan di manage</li>
                                <li>Pilih Akun facebook page yang terkait</li>
                            </ul>
                        </div>

                        @if(trim($loginUrl) != '' && trim($input['ig_page_id']) == '')
                            <a href="{{ $loginUrl }}" class="btn btn-success"> <i class="fab fa-facebook"></i> Facebook Login</a>
                        @endif
                        <hr>

                        @if(trim($input['ig_page_id']) != '')
                            <div class="card"><div class="card-body"><span class="badge badge-pill badge-success">Connected to Instagram</span></div></div>
                        @endif

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
@stop