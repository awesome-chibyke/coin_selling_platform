@php $pageName = 'Add Role(s)' @endphp
@php $active = 'roles'; @endphp
@extends('roles.man_dash')

@section('content')

    <div class="author-area-pro">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="author-widgets-single res-mg-t-30">

                        <form method="post" action="{{ route('store_role') }}">
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

                                    <div class="row field_holder">
                                        <div class="col-sm-6">
                                            <div class="widget-text-box">
                                                <h4>Roles</h4>
                                                <div class="form-select-list">
                                                    <input type="text" name="role[]" class="form-control"  placeholder="Role">
                                                    @error('role')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    @if($errors->has('role.*'))
                                                        <span class="invalid-feedback" role="alert">
                                                            @foreach($errors->get('role.*') as $message)
                                                                @foreach($message as $error)
                                                                    <strong>{{ $error }}</strong><br>
                                                                @endforeach
                                                            @endforeach
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="widget-text-box">
                                                <h4>Description</h4>
                                                <div class="form-select-list">
                                                    <textarea name="description[]" class="form-control" placeholder="Description"></textarea>
                                                    @error('description.0')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row" id="add_field">
                                        <div class="col-sm-12" style="margin-top: 20px">
                                            <button class="btn btn-dark" type="button" title="Add New Field for Role" id="roleFieldBtn" ><span class="fa fa-plus"></span> </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12" style="margin-top: 20px;">
                                            <button type="submit" class="btn btn-success btn-lg btn-block">Submit</button>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
