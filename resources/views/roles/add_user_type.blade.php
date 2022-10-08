@php $pageName = 'Add Type Of User(s)' @endphp
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

                                    <div class="row field_holder">
                                        <div class="col-sm-6">
                                            <div class="widget-text-box">
                                                <h4>Type of User</h4>
                                                <div class="form-select-list">
                                                    <input type="text" name="type_of_user[]" class="form-control"  placeholder="Type of User">

                                                    @if($errors->has('type_of_user.*'))
                                                        <span class="invalid-feedback" role="alert">
                                                            @foreach($errors->get('type_of_user.*') as $message)
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
                                            <button class="btn btn-dark" type="button" id="userTypeFieldBtn" title="Add New Field for Role"><span class="fa fa-plus"></span> </button>
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
