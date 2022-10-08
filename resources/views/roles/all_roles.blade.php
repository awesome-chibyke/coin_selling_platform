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

                                <div class="col-sm-12 table-responsive">

                                    <table id="myTable" class="table table-striped table-condensed">
                                        <thead style="color:white;">
                                        <tr>
                                            <th>S/N</th>
                                            <th class="hidden">Unique ID</th>
                                            <th>Role</th>
                                            <th>Description</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($all_roles) > 0)
                                            @php $no = 1 @endphp
                                            @foreach($all_roles as $k => $eachRole)
                                                <tr>
                                                    <td>{{$no}}</td>
                                                    <td class="hidden">{{$eachRole->unique_id}}</td>
                                                    <td><span class="label label-primary">{{$eachRole->role}}</span></td>
                                                    <td>{{$eachRole->description}}</td>
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
