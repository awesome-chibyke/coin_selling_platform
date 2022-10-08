@php $titleDescription = 'Edit User Profile' @endphp
@extends('layouts.logged_main')

@section('content')
<!-- content @s -->
<div class="nk-content nk-content-fluid">
    <div class="container-xl wide-lg">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Bank Account Lists</h3>
                        <div class="nk-block-des text-soft">
                            <p>You have total 2,595 users.</p>
                        </div>
                    </div><!-- .nk-block-head-content -->
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">

                            </div>
                        </div><!-- .toggle-wrap -->
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->
            <div class="nk-block">
                <div class="card card-bordered card-stretch">
                    <div class="card-inner-group">
                        <div class="card-inner position-relative card-tools-toggle">
                            <div class="card-title-group">
                                <div class="card-tools">

                                </div><!-- .card-tools -->
                                <div class="card-tools mr-n1">
                                    <ul class="btn-toolbar gx-1">

                                        <li class="btn-toolbar-sep"></li><!-- li -->
                                        <li>
                                            <div class="toggle-wrap">
                                                <a href="#" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-menu-right"></em></a>
                                                <div class="toggle-content" data-content="cardTools">
                                                    <ul class="btn-toolbar gx-1">
                                                        <li class="toggle-close">
                                                            <a href="#" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-arrow-left"></em></a>
                                                        </li><!-- li -->

                                                    </ul><!-- .btn-toolbar -->
                                                </div><!-- .toggle-content -->
                                            </div><!-- .toggle-wrap -->
                                        </li><!-- li -->
                                    </ul><!-- .btn-toolbar -->
                                </div><!-- .card-tools -->
                            </div><!-- .card-title-group -->
                            <div class="card-search search-wrap" data-search="search">
                                <div class="card-body">
                                    <div class="search-content">
                                        <a href="javascript:;" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                        <input type="text" class="form-control border-transparent form-focus-none" placeholder="Search by user or email">
                                        <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                    </div>
                                </div>
                            </div><!-- .card-search -->
                        </div><!-- .card-inner -->
                        <div class="card-inner p-0">
                            <div class="nk-tb-list nk-tb-ulist">
                                <div class="nk-tb-item nk-tb-head">

                                    <div class="nk-tb-col"><span class="sub-text">Bank Name</span></div>
                                    <div class="nk-tb-col tb-col-mb"><span class="sub-text">Beneficiary Name</span></div>
                                    <div class="nk-tb-col tb-col-md"><span class="sub-text">Account Number</span></div>
                                    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Status</span></div>
                                    <div class="nk-tb-col tb-col-lg"><span class="sub-text">Options</span></div>


                                </div>
                                @if(count($user_banks) > 0)
                                @foreach($user_banks as $eachBankDetails)
                                <!-- .nk-tb-item -->
                                <div class="nk-tb-item">


                                    <div class="nk-tb-col">
                                        <a href="javascript:;">
                                            <div class="user-card">
                                                <div class="user-avatar bg-primary">
                                                    <span>{{ $eachBankDetails->getInitials($eachBankDetails->bank_name) }}</span>
                                                </div>
                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $eachBankDetails->bank_name }} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                    <span>{{ $eachBankDetails->bank_code }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="nk-tb-col">
                                        <a href="javascript:;">
                                            <div class="user-card">
                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $eachBankDetails->beneficiary_name }} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    {{-- account_number 	bank_code 	beneficiary_name  bank_name --}}
                                    <div class="nk-tb-col tb-col-mb">
                                        <span class="tb-amount">{{ $eachBankDetails->account_number }} </span>
                                    </div>
                                    <div class="nk-tb-col tb-col-md">
                                        @php $statusDetails = $eachBankDetails->getStatus($eachBankDetails->status) @endphp
                                        <span class="tb-status text-{{ $statusDetails->class }}">{{ $statusDetails->keyword }}</span>
                                    </div>

                                    <div class="nk-tb-col nk-tb-col-tools">
                                        <ul class="nk-tb-actions gx-1">
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="{{ route('activate-bank', [$eachBankDetails->unique_id]) }}"><em class="icon ni ni-eye"></em><span>Make Active</span></a></li>
                                                            <li><a href="{{ route('delete-bank', [$eachBankDetails->unique_id]) }}"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- .nk-tb-item -->
                                @endforeach
                                @endif

                            </div><!-- .nk-tb-list -->
                        </div><!-- .card-inner -->

                        <div class="card-inner">
                            <div class="nk-block-between-md g-3">
                                <div class="g">
                                    <ul class="pagination justify-content-center justify-content-md-start">

                                        @php echo $my_pagination_links @endphp

                                    </ul><!-- .pagination -->
                                </div>
                                {{-- <div class="g">
                                    <div class="pagination-goto d-flex justify-content-center justify-content-md-start gx-3">
                                        <div>Page</div>
                                        <div>
                                            <select class="form-select form-select-sm" data-search="on" data-dropdown="xs center">
                                                <option value="page-1">1</option>
                                                <option value="page-2">2</option>
                                                <option value="page-4">4</option>
                                                <option value="page-5">5</option>
                                                <option value="page-6">6</option>
                                                <option value="page-7">7</option>
                                                <option value="page-8">8</option>
                                                <option value="page-9">9</option>
                                                <option value="page-10">10</option>
                                                <option value="page-11">11</option>
                                                <option value="page-12">12</option>
                                                <option value="page-13">13</option>
                                                <option value="page-14">14</option>
                                                <option value="page-15">15</option>
                                                <option value="page-16">16</option>
                                                <option value="page-17">17</option>
                                                <option value="page-18">18</option>
                                                <option value="page-19">19</option>
                                                <option value="page-20">20</option>
                                            </select>
                                        </div>
                                        <div>OF 102</div>
                                    </div>
                                </div> --}}
                                <!-- .pagination-goto -->
                            </div><!-- .nk-block-between -->
                        </div><!-- .card-inner -->
                    </div><!-- .card-inner-group -->
                </div><!-- .card -->
            </div><!-- .nk-block -->
        </div>
    </div>
</div>
<!-- content @e -->
@endsection
