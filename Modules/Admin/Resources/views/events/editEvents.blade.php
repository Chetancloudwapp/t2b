@extends('admin::admin.layout.layout')
@section('content')
<style>
    .multiImg{
        margin: 0px; 
        padding: 0px;
        list-style: none; 
        display: flex; 
        flex-wrap: wrap;
    }
    .multiImg li{
    width: 100px;
    position: relative;
    /* background: #00000091; */
    border: 1px solid #000;
    position: relative;
    margin-right: 15px;
    border-radius: 5px;
    padding: 43px;
    margin-top: 10px;

    }
    .multiImg li a {
    position: absolute;
    top: 4px;
    right: 7px;
    color: #000;
    /* background: #000; */
    /* padding: 5px; */
    /* border-radius: 50%; */
}
    .multiImg li img{width: 100%; height:100px}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
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
                            <h3 class="card-title nofloat"> <span>{{ $title}} </span> 
                            <a href="{{ url('admin/events')}}">
                                <button onClick="back();"
                                    class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                    data-modal="modal-13" style="float: right"> <i class="ti-control-backward m-r-5"></i> Back
                                </button>
                            </a></h3>
                        </div>
                        <div class="card-body">
                            <form name="eventsDetailForm" id="main" 
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{$events['id']}}">
                                <div class="col-md-12">
                                    <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">English</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Italian</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">German</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">French</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                                <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Name')}}<span class="mandatory cls" style="color:red; font-size:15px">*</span></label>
                                                                <input
                                                                    class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                                                    name="en_name" type="text"
                                                                    value="{{ old('name') }}" placeholder="Enter name"> 
                                                                @error('name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description</label>
                                                                <textarea
                                                                class="form-control {{ $errors->has('description') ? 'form-control-danger' : ''}}"
                                                                name="en_description" type="message"
                                                                    placeholder="Enter Description">{{ old('description') }}</textarea>  
                                                                @error('description')
                                                                    <div class="col-form-alert-label">
                                                                    {{$message}}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Name')}}<span class="mandatory cls" style="color:red; font-size:15px">*</span></label>
                                                                <input
                                                                    class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                                                    name="it_name" type="text"
                                                                    value="{{ old('name') }}" placeholder="Enter name"> 
                                                                @error('name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description</label>
                                                                <textarea
                                                                class="form-control {{ $errors->has('description') ? 'form-control-danger' : ''}}"
                                                                name="it_description" type="message"
                                                                    placeholder="Enter Description">{{ old('description') }}</textarea>  
                                                                @error('description')
                                                                    <div class="col-form-alert-label">
                                                                    {{$message}}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Name')}}<span class="mandatory cls" style="color:red; font-size:15px">*</span></label>
                                                                <input
                                                                    class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                                                    name="de_name" type="text"
                                                                    value="{{ old('name') }}" placeholder="Enter name"> 
                                                                @error('name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description</label>
                                                                <textarea
                                                                class="form-control {{ $errors->has('description') ? 'form-control-danger' : ''}}"
                                                                name="de_description" type="message"
                                                                    placeholder="Enter Description">{{ old('description') }}</textarea>  
                                                                @error('description')
                                                                    <div class="col-form-alert-label">
                                                                    {{$message}}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Name')}}<span class="mandatory cls" style="color:red; font-size:15px">*</span></label>
                                                                <input
                                                                    class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                                                    name="fr_name" type="text"
                                                                    value="{{ old('name') }}" placeholder="Enter name"> 
                                                                @error('name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description</label>
                                                                <textarea
                                                                class="form-control {{ $errors->has('description') ? 'form-control-danger' : ''}}"
                                                                name="fr_description" type="message"
                                                                    placeholder="Enter Description">{{ old('description') }}</textarea>  
                                                                @error('description')
                                                                    <div class="col-form-alert-label">
                                                                    {{$message}}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                {{-- <input type="hidden" name="id" value="{{$events['id']}}"> --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('eventdate') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('EventDate')}}
                                        </label>
                                        <input
                                            class="form-control {{ $errors->has('eventdate') ? 'form-control-danger' : '' }}"
                                            name="eventdate" type="date"

                                            value="{{ date("Y-m-d",strtotime($events['eventdate'])) }}">      
                                        @error('eventdate')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('banner_image') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Banner Image</label>
                                        <input type="file"
                                            class="form-control {{ $errors->has('banner_image') ? 'form-control-danger' : '' }}"
                                            onchange="loadFile(event,'image_1')" name="banner_image">
                                        @if(!empty($events['banner_image']))
                                        <input type="hidden" name="current_image" value="{{ $events['banner_image'] }}">
                                        @endif
                                        @error('banner_image')
                                        <div class="col-form-alert-label">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="media-left">
                                        <a href="#" class="profile-image">
                                        <img class="user-img img-css" id="image_1" style="width:20%;"
                                            src="{{ $events['banner_image'] != '' ? asset('uploads/events/bannerImage/'. $events['banner_image']) : asset('assets/upload/placeholder.png') }}">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('images') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Gallery Images : (Recommend Size: Less than 2 Mb)</label>
                                        <input type="file"
                                            class="form-control {{ $errors->has('images') ? 'form-control-danger' : '' }}"
                                            onchange="loadFile(event,'image_1')"  name="images[]" multiple="" id="images">
                                        @error('images')
                                        <div class="col-form-alert-label">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="media-left">
                                        <ul class="multiImg">
                                            @foreach($events['galleryimages'] as $value)
                                            <li>
                                                @if($value['event_id'] == $events['id'])
                                                    <a target="_blank" href="{{ asset('uploads/events/galleryImages/'. $value['images'])}}"><img src="{{ $value['images'] != '' ? asset('uploads/events/galleryImages/'. $value['images']) : asset('assets/upload//placeholder.png') }}"  class="user-img img-css" id="image_1">
                                                    </a>&nbsp;
                                                    <a href="{{ url('admin/events/deleteImage/'. $value['id'])}}" title="Delete Image" name="product" title="Delete Gallery Image"> <i class="fa-solid fa-trash" style="color: red;"></i> 
                                                    </a>
                                                @endif  
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>                            
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('status') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Status</label>
                                        <select id="status" name="status" class="form-control stock">
                                            <option value="Active">Active</option>
                                            {{-- <option value="Pending">Pending</option> --}}
                                            {{-- <option value="Reject">Reject</option> --}}
                                            <option value="Deactive"
                                                {{ $events['status'] == 'Deactive' ? 'selected' : '' }}>Deactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer" style="float: right;"><button type="submit" class="btn btn-primary">Submit</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

