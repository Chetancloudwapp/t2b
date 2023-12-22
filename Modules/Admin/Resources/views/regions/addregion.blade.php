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
                            <a href="{{ url('admin/region')}}">
                                <button onClick="back();"
                                    class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                    data-modal="modal-13" style="float: right"> <i class="fa-solid fa-backward"></i>&nbsp;&nbsp; Back
                                </button>
                            </a></h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ url('admin/region/add') }}" 
                            method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                   
                                    <div class="col-md-6">
                                        <div class="form-group mb-3 {{ $errors->has('country') ? 'has-danger' : '' }}">
                                            <label class="col-form-label">{{('Country')}}*</label>
                                            <select class="form-control" id="country" name="country">
                                                <option value="">Select Country</option>
                                                @foreach($get_countries as $country)
                                                    {{-- <option value="{{ $value['id']}}" {{ $value['id'] == $products['color'] ? 'selected' : ''}}>{{ $value['name']}}</option> --}}
                                                    <option value="{{ $country['id']}}">{{ $country['name']}}</option>
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
                                        <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                            <label class="col-form-label">{{('Name')}}*</label>
                                            <input
                                                class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                                name="name" type="text"
                                                value="{{ old('name') }}" placeholder="Enter name">      
                                            @error('name')
                                                <div class="col-form-alert-label">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer"> <button type="submit" class="btn btn-primary">{{ $common['button'] }}</button> </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

