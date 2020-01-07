@extends('cms.layout.master')
@section('title', 'Create User')
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
                        <div class="col-sm-8 offset-sm-2">
                            <form action="{{ route('users.store') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">     
                                    <strong>Name:</strong>                               
                                    <input id="Name" type="text" name="name" class="form-control" placeholder="Name">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlInput1">Email</label>
                                    <input name="email" type="email" class="form-control" id="exampleFormControlInput1" placeholder="Email">
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlSelect1">Role</label>
                                    <select name="roles" class="form-control">
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}">{{ $role }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlInput1">Password</label>
                                    <input id="password" type="password" name="password" class="form-control" id="exampleFormControlInput1" placeholder="Password" >
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="exampleFormControlInput1">Confirm Password</label>
                                    <input id="confirmpassword" type="password" name="confirm-password" class="form-control" id="exampleFormControlInput1" placeholder="Confirm password" required>
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

@stop