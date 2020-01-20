@extends('cms.layout.master')
@section('title', 'Create Probem List')
@section('parentPageTitle', 'Problem List')
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
                            <form action="{{ route('list.store') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">     
                                    <strong>Title:</strong>                               
                                    <input id="Name" type="text" name="title" class="form-control" placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label"><strong>Topic:</strong></label>
                                    <select class="form-control" name="topic_id">
                                        @foreach($topics as $idx => $topic)
                                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">     
                                    <strong>Description:</strong>                               
                                    <textarea name="description" rows="4" class="form-control"></textarea>
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