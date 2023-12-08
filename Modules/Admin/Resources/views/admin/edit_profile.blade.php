@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Setting</a></li>
                        <li class="breadcrumb-item active">Edit Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header text-right">
                            <h3 class="card-title">
                                <a href="{{ url('admin/view_profile')}}">
                                    <button onClick="back();"
                                        class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                        data-modal="modal-13" style="float: right"> <i class="ti-control-backward m-r-5"></i> Back
                                    </button>
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            @if(Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @if(Session::has('error_message'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> {{ Session::get('error_message')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            <form method="POST" action="{{ url('admin/edit_profile')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="profile_pic">
                                                <img id="blah" src="https://www.treasury.gov.ph/wp-content/uploads/2022/01/male-placeholder-image.jpeg" />
                                                <input type="file" id="imgInp">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputEmail1">Full name</label> 
                                                        <input type="text" id="name" value="{{ Auth::guard('admin')->user()->name }}" name="name" class="form-control" placeholder="Enter full name"> 
                                                    </div>
                                                    @error('name')
                                                        <div class="col-form-alert-label">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputPassword1">Email id</label> 
                                                        <input type="email" id="email" name="email" value="{{ Auth::guard('admin')->user()->email }}" class="form-control" placeholder="Enter email id"> 
                                                    </div>
                                                    @error('email')
                                                        <div class="col-form-alert-label">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputPassword1">Phone number</label> 
                                                        <input type="text" class="form-control" placeholder="Enter phone number"> 
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputPassword1">Registration time</label> 
                                                        <input type="date" class="form-control" placeholder=""> 
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg_transparent"> 
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary">Update Profile</button> 
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
