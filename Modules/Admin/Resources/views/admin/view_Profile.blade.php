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
                        <li class="breadcrumb-item active">Profile</li>
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
                                <a href="{{ url('admin/edit_profile')}}" class="btn btn-primary"> <i class="fas fa-pencil"></i> Edit Profile </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <div class="profile_pic">
                                                <img src="https://file.xunruicms.com/admin_html/assets/pages/media/profile/profile_user.jpg" />
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputEmail1">Full name</label> 
                                                        <h6>{{ Auth::guard('admin')->user()->name}} </h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputPassword1">Email id</label> 
                                                        <h6> {{ Auth::guard('admin')->user()->email}} </h6>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputPassword1">Phone number</label> 
                                                        <h6> 9652301478 </h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"> 
                                                        <label for="exampleInputPassword1">Registration time</label> 
                                                        15-08-2023
                                                    </div>
                                                </div> --}}
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
    </section>
</div>
@endsection

