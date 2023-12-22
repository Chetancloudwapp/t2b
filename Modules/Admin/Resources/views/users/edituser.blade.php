@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $common['title'] }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $common['title'] }}</li>
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
                        <div class="card-header">
                            <h3 class="card-title nofloat"> <span>{{ $common['heading_title']}} </span>
                            <a href="{{ url('admin/user')}}">
                                <button onClick="back();"
                                    class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                    data-modal="modal-13" style="float: right"> <i class="fa-solid fa-backward"></i>&nbsp;&nbsp; Back
                                </button>
                            </a></h3>
                        </div>
                        <div class="card-body">
                            <form name="userDetailForm" id="main" 
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{$user['id']}}">
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Name')}}</label>
                                        <input
                                            class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                            name="name" type="text"
                                            value="{{ old('name', $user['name']) }}" placeholder="Enter name">      
                                        @error('name')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('email') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Email')}}</label>
                                        <input
                                            class="form-control {{ $errors->has('email') ? 'form-control-danger' : '' }}"
                                            name="email" type="text"
                                            value="{{ old('email', $user['email']) }}" placeholder="Enter your email">      
                                        @error('email')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('country') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Country')}}</label>
                                        <select class="form-control" id="country" name="country">
                                            <option value="">Select Country</option>
                                            @foreach($get_countries as $country)
                                                <option value="{{ $country['id']}}" {{ $country['id'] == $user['country_id'] ? 'selected' : ''}}>{{ $country['name']}}</option>
                                                {{-- <option value="{{ $country['id']}}">{{ $country['name']}}</option> --}}
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('region') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Region</label>
                                        <select class="form-control" name="region" id="region">
                                            <option value="0">Select region</option>                                         
                                                
                                        </select>
                                        @error('region')
                                              <div class="col-form-alert-label">
                                                  {{$message}}
                                             </div>  
                                        @enderror
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="col-form-label">Phone Number</label>
                                        <div class="row">
                                            <div class="col-2 pr-0"> 
                                                <select class="form-control" name="country_code" id="country_code">
                                                    {{-- <option value="">+1</option> --}}
                                                    @foreach($get_countries as $value)
                                                        <option value="{{ $value['phonecode'] }}" {{ $value['phonecode'] == $user['country_code'] ? 'selected' : ''}}>{{ $value['phonecode']}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                            <div class="col-16 pl-0">
                                        <input placeholder="Enter Phone number" value="{{ old('phone_number', $user['phone_number']) }}" class="form-control" name="phone_number" type="text" value="">
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('company_name') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Company Name')}}</label>
                                        <input
                                            class="form-control {{ $errors->has('company_name') ? 'form-control-danger' : '' }}"
                                            name="company_name" type="text"
                                            value="{{ old('company_name', $user['company_name']) }}" placeholder="Enter Company name">      
                                        @error('company_name')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('image') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Image</label>
                                        <input type="file"
                                            class="form-control {{ $errors->has('image') ? 'form-control-danger' : '' }}"
                                            onchange="loadFile(event,'image_1')" name="image">
                                        @error('image')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="media-left">
                                        <a href="#" class="profile-image">
                                            <img class="user-img img-css" id="image_1" style="width:20%;"
                                                src="{{ $user['image'] != '' ? asset('uploads/userimage/'. $user['image']) : asset('assets/upload/placeholder.png') }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('status') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Status</label>
                                        <select id="status" name="status" class="form-control stock">
                                            <option value="Active">Active</option>
                                            <option value="Pending">Pending</option>
                                            <option value="Reject">Reject</option>
                                        </select>
                                        @error('status')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('status_reason') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Status Reason')}}</label>
                                        <input
                                            class="form-control {{ $errors->has('status_reason') ? 'form-control-danger' : '' }}"
                                            name="status_reason" type="text"
                                            value="{{ old('status_reason', $user['status_reason']) }}" placeholder="Enter Status Reason">      
                                        @error('status_reason')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer"> <button type="submit" class="btn btn-primary">{{$common['button']}}</button> </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
      $('#country').change(function () {
         var countryId = $(this).val();  
        //  alert(countryId);       
         $.ajax({
            url: "{{ url('admin/get-regions')}}", 
            method: 'POST',
            data: { country_id : countryId, _token: "{{ csrf_token() }}"},
            success: function (data) {
            //    alert(data);
               var regionDropdown = $('#region');
               regionDropdown.empty(); 
               regionDropdown = $('#region').html('<option value="">Select Region</option>'); 
               $.each(data, function (key, value) {
                  regionDropdown.append($('<option value="">Select Region</option>').attr('value', key).text(value));
               });
            }
         });
      });
   });
</script>
@endsection

