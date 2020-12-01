@extends('cms.layout.master')
@section('title', 'Users')
@section('parentPageTitle', 'Users')
@section('page-style')
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-datatable/dataTables.bootstrap4.min.css')}}"/>
@stop
@section('content')

<!-- Exportable Table -->
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="header">
                <h2><strong>Users</strong> List </h2>
                <a class="btn btn-success" href="{{ route('users.create') }}">Create</a>
            </div>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">

                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Facebook Status</th>
                                <th class="text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $key => $user)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge badge-pill badge-success"> {{ implode('</span> <span class="badge badge-pill badge-success">', $user->getRoleNames()->toArray()) }}</span></td>
                                <td>
                                    
                                    @if(trim($user->ig_page_id) != '')
                                        <span class="badge badge-pill badge-success">Connected</span>
                                    @else
                                        <span class="badge badge-pill badge-info">Not Connected</span>
                                    @endif

                                </td>
                                <td>
                                    <a class="btn btn-primary" href="{{ route('users.edit',$user->user_id) }}">Edit</a>
                                    <a class="btn btn-info" href="{{ route('cms.users.fb',$user->user_id) }}">Connect Facebook</a>
                                    <form action="{{ route('users.destroy',$user->user_id) }}" method="POST"
                                        style="display:inline">
                                        @method('DELETE')
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('page-script')
<script src="{{asset('assets/bundles/datatablescripts.bundle.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-datatable/buttons/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.colVis.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-datatable/buttons/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/js/pages/tables/jquery-datatable.js')}}"></script>
@stop