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
                            <a href="{{ url('admin/news')}}">
                                <button onClick="back();"
                                    class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                    data-modal="modal-13" style="float: right"><i class="fa-solid fa-backward"></i>&nbsp;&nbsp; Back
                                </button>
                            </a></h3>
                        </div>
                        <div class="card-body">
                            <form name="newsDetailForm" id="main" 
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{$news['id']}}">
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
                                                            <div class="form-group mb-3 {{ $errors->has('en_name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Title')}}*</label>
                                                                <input
                                                                    class="form-control {{ $errors->has('en_name') ? 'form-control-danger' : '' }}"
                                                                    name="en_name" type="text"
                                                                    value="{{ old('en_name') }}" placeholder="Enter name"> 
                                                                @error('en_name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('en_description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description*</label>
                                                                <textarea
                                                                class="form-control summernote {{ $errors->has('en_description') ? 'form-control-danger' : ''}}"
                                                                name="en_description" type="message"
                                                                    placeholder="Enter Description">{{ old('en_description') }}</textarea>  
                                                                @error('en_description')
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
                                                            <div class="form-group mb-3 {{ $errors->has('it_name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Title')}}*</label>
                                                                <input
                                                                    class="form-control {{ $errors->has('it_name') ? 'form-control-danger' : '' }}"
                                                                    name="it_name" type="text"
                                                                    value="{{ old('it_name') }}" placeholder="Enter name"> 
                                                                @error('it_name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('it_description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description*</label>
                                                                <textarea
                                                                class="form-control summernote {{ $errors->has('it_description') ? 'form-control-danger' : ''}}"
                                                                name="it_description" type="message"
                                                                    placeholder="Enter Description">{{ old('it_description') }}</textarea>  
                                                                @error('it_description')
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
                                                            <div class="form-group mb-3 {{ $errors->has('de_name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Title')}}*</label>
                                                                <input
                                                                    class="form-control {{ $errors->has('de_name') ? 'form-control-danger' : '' }}"
                                                                    name="de_name" type="text"
                                                                    value="{{ old('de_name') }}" placeholder="Enter name"> 
                                                                @error('de_name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('de_description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description*</label>
                                                                <textarea
                                                                class="form-control summernote {{ $errors->has('de_description') ? 'form-control-danger' : ''}}"
                                                                name="de_description" type="message"
                                                                    placeholder="Enter Description">{{ old('de_description') }}</textarea>  
                                                                @error('de_description')
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
                                                            <div class="form-group mb-3 {{ $errors->has('fr_name') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">{{('Title')}}</label>
                                                                <input
                                                                    class="form-control {{ $errors->has('fr_name') ? 'form-control-danger' : '' }}"
                                                                    name="fr_name" type="text"
                                                                    value="{{ old('fr_name') }}" placeholder="Enter name"> 
                                                                @error('fr_name')
                                                                    <div class="col-form-alert-label">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group mb-3 {{ $errors->has('fr_description') ? 'has-danger' : '' }}">
                                                                <label class="col-form-label">Description*</label>
                                                                <textarea
                                                                class="form-control summernote {{ $errors->has('fr_description') ? 'form-control-danger' : ''}}"
                                                                name="fr_description" type="message"
                                                                    placeholder="Enter Description">{{ old('fr_description') }}</textarea>  
                                                                @error('fr_description')
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
                                {{-- <input type="hidden" name="id" value="{{$news['id']}}"> --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('banner_image') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Banner Image*</label>
                                        <input type="file"
                                            class="form-control {{ $errors->has('banner_image') ? 'form-control-danger' : '' }}"
                                            onchange="loadFile(event,'image_1')" name="banner_image">
                                        @if(!empty($news['banner_image']))
                                        <input type="hidden" name="current_image" value="{{ $news['banner_image'] }}">
                                        @endif
                                        @error('banner_image')
                                        <div class="col-form-alert-label">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="media-left">
                                        <a href="#" class="profile-image">
                                        <img class="user-img img-css" id="image_1"
                                            src="{{ $news['banner_image'] != '' ? asset('uploads/news/bannerImage/'. $news['banner_image']) : asset('assets/upload/placeholder.png') }}">
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
                                            {{-- @foreach($news['images'] as $value)
                                            <li>
                                                @if($value['product_id'] == $products['id'])
                                                    <a target="_blank" href="{{ asset('uploads/news/galleryImages/'. $value['image'])}}"><img src="{{ $value['image'] != '' ? asset('uploads/products/galleryImages/small/'. $value['image']) : asset('assets/upload//placeholder.png') }}"  class="user-img img-css" id="image_1">
                                                    </a>&nbsp;
                                                    <a href="{{ url('admin/product/deleteImage/'. $value['id'])}}" title="Delete Image" name="product" title="Delete Product Image"> <i class="fa-solid fa-trash" style="color: red;"></i> 
                                                    </a>
                                                @endif  
                                            </li>
                                            @endforeach --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('latitude') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Latitude')}}</label>
                                        <input
                                            class="form-control {{ $errors->has('latitude') ? 'form-control-danger' : '' }}"
                                            name="latitude" type="text"
                                            value="{{ old('latitude') }}" placeholder="Enter Latitude"> 
                                        @error('latitude')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>                            
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('longitude') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Longitude')}}</label>
                                        <input
                                            class="form-control {{ $errors->has('longitude') ? 'form-control-danger' : '' }}"
                                            name="longitude" type="text"
                                            value="{{ old('longitude') }}" placeholder="Enter longitude"> 
                                        @error('longitude')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
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
                                                {{ $news['status'] == 'Deactive' ? 'selected' : '' }}>Deactive
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
                            <div class="card-footer"> <button type="submit" class="btn btn-primary">{{$common['button']}}</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

