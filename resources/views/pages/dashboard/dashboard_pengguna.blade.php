@extends('layouts.contentNavbarLayout')

@section('title', $title.' • '.config('variables.templateName'))

@section('page-style')
    @vite('resources/assets/css/custom.scss')
@endsection

@section('content')
    <div class="row">
        <div class="col-xxl-8 mb-6 order-0">
            <div class="card">
                <div class="d-flex align-items-start row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <p class="mb-6">You have done 72% more sales today.<br>Check your new badge in your profile.
                            </p>

                            <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-6">
                            <img src="{{asset('assets/img/illustrations/man-with-laptop.png')}}" height="175"
                                 class="scaleX-n1-rtl" alt="View Badge User">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
