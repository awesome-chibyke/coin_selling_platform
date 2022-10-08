@php $pageName = 'All User Types' @endphp
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

                                <div class="col-sm-12 table-responsive">

                                    <table id="myTable" class="table table-striped table-condensed">
                                        <thead style="color:white;">
                                        <tr>
                                            <th>S/N</th>
                                            <th class="hidden">Unique ID</th>
                                            <th>User types</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($all_user_type) > 0)
                                            @php $no = 1 @endphp
                                            @foreach($all_user_type as $k => $eachUserType)
                                                <tr>
                                                    <td>{{$no}}</td>
                                                    <td class="hidden">{{$eachUserType->unique_id}}</td>
                                                    <td><span class="label label-primary">{{$eachUserType->type_of_user}}</span></td>
                                                    <td>{{$eachUserType->description}}</td>
                                                    <td>

                                                        <div class="btn-group">
                                                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle btn btn-primary">Options</button>
                                                            <div tabindex="-1" aria-hidden="true" role="menu" class="dropdown-menu">
                                                                <button type="button" tabindex="0" class=" btn btn-block"><a href="{{route('add_role_for_user', [$eachUserType->unique_id])}}">Add New Roles</a></button>

                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                                @php $no++ @endphp
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


