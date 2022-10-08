<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="index.html" style="width: 80px;" ><img style="width: 80px;" class="main-logo" src="{{asset('img/logo/GuoLogo.png')}}" alt="" /></a>
            {{--<strong style="width: 80px;" ><img style="width: 100%;" src="img/logo/logosn.png" alt="" /></strong>--}}
        </div>
        <div class="nalika-profile">
            <div class="profile-dtl">
                @php //$link = auth()->user()->returnLink(); @endphp

                <a href="#">
                    {{--<img src="{{asset((auth()->user()->profile_image === null) ? 'img/alt_image.png' : $link.'users/'.auth()->user()->profile_image)}}" alt="{{env('APP_NAME', 'LARAVEL')}}" class="img-circle circle-border m-b-md" style="max-width: 100px;" />--}}
                    {{--<img src="{{asset('mdash/img/notification/4.jpg')}}" alt="" />--}}
                </a>
                <h2>{{--{{auth()->user()->name}}--}} {{--<span class="min-dtn">Das</span>--}}</h2>
            </div>
            {{--<div class="profile-social-dtl">
                <ul class="dtl-social">
                    <li><a href="#"><i class="icon nalika-facebook"></i></a></li>
                    <li><a href="#"><i class="icon nalika-twitter"></i></a></li>
                    <li><a href="#"><i class="icon nalika-linkedin"></i></a></li>
                </ul>
            </div>--}}
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">

                    <li>
                        <a href="{{route('home')}}" aria-expanded="false"><i class="icon nalika-home icon-wrap"></i> <span class="mini-click-non">Dashboard</span></a>
                    </li>



                    {{--@if(auth()->user()->privilegeChecker('view_roles'))--}}
                    <li class="{{@$active === 'roles' ? 'active':''}}">
                        <a class="has-arrow" href="javascript:;" aria-expanded="false"><i class="icon nalika-smartphone-call icon-wrap"></i> <span class="mini-click-non">Roles</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="{{route('add_roles')}}"><span class="mini-sub-pro">Add New Roles</span></a></li>
                            <li><a href="{{route('add_user_type')}}"><span class="mini-sub-pro">Add User type</span></a></li>
                            <li><a href="{{route('view_all_roles')}}"><span class="mini-sub-pro">View Roles</span></a></li>
                            <li><a href="{{route('all_user_type')}}"><span class="mini-sub-pro">View User Types</span></a></li>
                        </ul>
                    </li>
                    {{--@endif--}}


                    {{--<li style="margin-bottom: 200px;">
                        <a class="has-arrow" href="javascript:;" aria-expanded="false"><i class="icon nalika-refresh-button icon-wrap"></i> <span class="mini-click-non">Logout</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="javascript:;" onclick="logoutFuntion()"><span class="mini-sub-pro">Logout</span></a></li>
                        </ul>
                    </li>--}}

                </ul>
            </nav>
        </div>
    </nav>
</div>