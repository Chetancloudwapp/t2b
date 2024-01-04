@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Investments</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Investments</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @csrf
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="card card-primary">
                        {{-- <div class="card-header">
                            <h3 class="card-title nofloat">Member Details
                                <a href="{{ url('admin/user')}}">
                                    <button onClick="back();"
                                        class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                        data-modal="modal-13" style="float: right"> <i class="fa-solid fa-backward"></i>&nbsp;&nbsp; Back
                                    </button>
                                </a>
                            </h3>
                        </div> --}}
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Created By</label>
                                            <h6>{{ $get_investments->get_investments->name }} </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Email</label>
                                            <h6>{{ $get_investments->get_investments->email }} </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Company Name</label>
                                            <h6>{{ $get_investments->get_investments->company_name }} </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="investment_title">Investment Title</label>
                                            <h6>{{ $get_investments->investment_title}} </h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="investment_detail">Investment Detail</label>
                                            <h6>{{ $get_investments->investment_detail}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Country Code</label>
                                            <h6>{{ $get_investments->get_investments->country_code }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Phone Number</label>
                                            <h6>{{ $get_investments->get_investments->phone_number }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Country</label>
                                            <h6>{{ $get_investments->get_investments->country->name }}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Region</label>
                                            <h6>
                                                {{ $get_investments->get_investments->get_region->name }}
                                            </h6>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Status</label>
                                            <h6></h6>
                                        </div>
                                    </div> --}}
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Image </label>
                                            <section class="uploadImg">
                                                <ul>
                                                    <li>
                                                        <img
                                                            src="{{ $get_users->image !='' ? asset('uploads/userimage/'. $get_users->image) : asset('uploads/placeholder/default_user.png') }}">
                                                    </li>
                                                </ul>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <div class="specific_sec">
                                    <h3 style="margin-bottom: 15px; font-size: 22px; font-weight: 800;">Offers List</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Image</th>
                                                <th>contact_email</th>
                                                <th>offer_detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($get_users->offers as $key => $value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td><img class="tbl-img-css rounded-circle" width="50px"
                                                        src="{{ $value['image'] !='' ? asset('uploads/offers/'. $value['image']) : asset('uploads/placeholder/default_user.png') }}">
                                                </td>
                                                <td>{{ $value->contact_email }}</td>
                                                <td>{{ $value->offer_detail }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="specific_sec">
                                    <h3 style="margin-bottom: 15px; font-size: 22px; font-weight: 800;">Events List</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($get_users['eventfeedback'] as $key => $value)
                                            <tr>
                                                <td><img class="tbl-img-css rounded-circle" width="50px"
                                                        src="{{ $value->events->banner_image !='' ? asset('uploads/events/bannerImage/'. $value->events->banner_image) : asset('uploads/placeholder/default_user.png') }}">
                                                </td>
                                                <td>{{ $value->events['name'] ?? '' }}</td>
                                                <td>{{ $value->events['description'] ??''}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="specific_sec">
                                    <h3 style="margin-bottom: 15px; font-size: 22px; font-weight: 800;">Investments</h3>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Title</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($get_users->investments as $key => $value)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $value->investment_title }}</td>
                                                <td>{{ $value->investment_detail }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> --}}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection