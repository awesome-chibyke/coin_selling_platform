@php $pageName = 'All Roles' @endphp
@php $active = 'roles' @endphp
@extends('roles.man_dash')

@section('content')

    <div class="author-area-pro">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="author-widgets-single res-mg-t-30">

                        <form method="post" action="{{ route('store_user_type') }}">
                            @csrf
                            <div class="row">

                                <div class="col-sm-12">
                                    @if(Session::has('status'))
                                        <p class="alert alert-success text-center"  role="alert">

                                            {{ Session::get('status') }}

                                        </p>
                                    @elseif(Session::has('error_message'))
                                        <p class="alert alert-danger text-center text-white" role="alert">

                                            {{ Session::get('error_message') }}

                                        </p>
                                    @endif
                                </div>

                                <div class="col-sm-12">
                                    <h3 class="text-left" style="color:white;">Add/View Roles for User of type: <strong>{{strtoupper($user_type->type_of_user)}}</strong></h3>
                                </div>

                                <div class="col-sm-12 text-right">
                                    <div class="row">
                                        <div class="col-12">
                                            <button data-action="assign" type="button" class="btn btn-success assignRoles">Assign Selected Roles to Users of type: <strong>{{strtoupper($user_type->type_of_user)}}</strong></button>

                                            <button data-action="remove"  type="button" class="btn btn-success assignRoles">Un-assign Selected Roles</button>
                                        </div>
                                        <div class="col-6 pull-right">
                                            <label>Change User Type</label>
                                            <select class="form-control" onChange="window.location.href = this.value">
                                                @foreach ($all_user_type as $eachUserType)
                                                    <option {{ $eachUserType->unique_id === $user_type->unique_id ? 'selected':'' }} value="{{ route('add_role_for_user', [$eachUserType->unique_id]) }}">{{ $eachUserType->type_of_user }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 table-responsive" style="margin-top: 20px;">
                                    <table id="myTable" class="table table-striped table-condensed">
                                        <thead style="color:white;">
                                        <tr>
                                            <th>S/N</th>
                                            <td><input type="checkbox" onclick="checkAll()" class="mainCheckBox" id="mainCheckBox" /></td>
                                            <th class="hidden">Unique ID</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Description</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($all_roles) > 0)
                                            @php $no = 1 @endphp
                                            @foreach($all_roles as $k => $eachRole)
                                            @php $color = $no%2 == 0 ? 'white':'' @endphp
                                                <tr style="color:{{ $color }};">
                                                    @php $previledges = new \App\Models\Roles\Previledges(); @endphp
                                                    @php $previledges = $previledges->returnUserTypeRoleStatus($user_type->unique_id, $eachRole->unique_id); @endphp


                                                    <td>{{$no}}</td>
                                                    <td>

                                                        <input type="checkbox" class="smallCheckBox" value="{{$eachRole->unique_id}}" />
                                                    </td>
                                                    <td class="hidden">{{$eachRole->unique_id}}</td>
                                                    <td>{{$eachRole->role}}</td>
                                                    <td><span class="label label-{{$previledges['label']}}">{{$previledges['status']}}</span></td>
                                                    <td>{{$eachRole->description}}</td>
                                                </tr>
                                                @php $no++ @endphp
                                            @endforeach

                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12 ">
                                    <input type="hidden" id="typeOfUserIdHolder" value="{{$user_type->unique_id}}" />
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('content')

        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Dashboard</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="{{route('home')}}">Dashboard</a></li>
                                    <li><a href="{{route('all_user_type')}}">Roles</a></li>
                                    <li class="active">Assign Roles To a User Type</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="animated fadeIn">

                <div class="card">
                    {{--<div class="card-header">
                        <strong class="card-title">Create User Types</strong>
                    </div>--}}
                    <div class="card-body">
                        <!-- Credit Card -->
                        <div id="pay-invoice">
                            <div class="card-body">
                                <div class="card-title">
                                    <h3 class="text-left">Add/View Roles for User of type: <strong>{{strtoupper($user_type->type_of_user)}}</strong></h3>
                                </div>
                                <hr>

                                <div class="col-sm-12 text-right">
                                    <button onclick="assignRoles(this, 'assign')" type="button" class="btn btn-success">Assign Selected Roles to Users of type: <strong>{{strtoupper($user_type->type_of_user)}}</strong></button>
                                    <button onclick="assignRoles(this, 'remove')" type="button" class="btn btn-success">Un-assign Selected Roles</button>
                                </div>

                                <div class="col-sm-12 " style="margin-top: 20px;">
                                    <table id="myTable" class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <td><input type="checkbox" onclick="checkAll()" class="mainCheckBox" /></td>
                                            <th>Unique ID</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Description</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($all_roles) > 0)
                                            @php $no = 1 @endphp
                                            @foreach($all_roles as $k => $eachRole)
                                                <tr>
                                                    @php $previledges = new \App\Models\Roles\Previledges(); @endphp
                                                    @php $previledges = $previledges->returnUserTypeRoleStatus($user_type->unique_id, $eachRole->unique_id); @endphp

                                                    <td>{{$no}}</td>
                                                    <td>

                                                        <input type="checkbox" class="smallCheckBox" value="{{$eachRole->unique_id}}" />
                                                    </td>
                                                    <td>{{$eachRole->unique_id}}</td>
                                                    <td>{{$eachRole->role}}</td>
                                                    <td><span class="btn btn-{{$previledges['label']}}">{{$previledges['status']}}</span></td>
                                                    <td>{{$eachRole->description}}</td>
                                                </tr>
                                                @php $no++ @endphp
                                            @endforeach

                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-sm-12 ">
                                    <input type="hidden" id="typeOfUserIdHolder" value="{{$user_type->unique_id}}" />
                                </div>


                            </div>
                        </div>

                    </div>
                </div> <!-- .card -->

            </div>
        </div>
@endsection
