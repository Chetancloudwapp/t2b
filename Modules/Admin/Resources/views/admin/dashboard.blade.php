@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <a href="{{ url('admin/user') }}">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $total_members }}</h3>
                                <p>Members</p>
                            </div>
                            <div class="icon"> 
                                <i class="fa-solid fa-user"></i> 
                            </div> 
                            {{-- <a href="{{ url('admin/user')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ url('admin/events') }}">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $total_events }}<sup style="font-size: 20px"></sup></h3>
                                <p>Events</p>
                            </div>
                            <div class="icon"> 
                                <i class="fa-solid fa-calendar-days"></i> 
                            </div> 
                            {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                    <a href="{{ url('admin/news')}}">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $total_news }}</h3>
                                <p>News</p>
                            </div>
                            <div class="icon"> 
                                <i class="fa-regular fa-newspaper"></i> 
                            </div> 
                            {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-6">
                <a href="{{ url('admin/photos')}}">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $total_photos }}</h3>
                            <p>Photos</p>
                        </div>
                        <div class="icon"> 
                            <i class="fa-regular fa-image"></i> 
                        </div> 
                        {{-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> --}}
                    </div>
                </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection