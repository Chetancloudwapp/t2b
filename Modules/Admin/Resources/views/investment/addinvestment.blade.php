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
                            <a href="{{ url('admin/investment')}}">
                                <button onClick="back();"
                                    class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger"
                                    data-modal="modal-13" style="float: right"> <i class="fa-solid fa-backward"></i>&nbsp;&nbsp; Back
                                </button>
                            </a></h3>
                        </div>
                        <div class="card-body">
                            <form name="InvestDetailForm" id="main" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="form-group mb-3 {{ $errors->has('investment_title') ? 'has-danger' : '' }}">
                                            <label class="col-form-label">{{('Investment Title')}}*</label>
                                            <input
                                                class="form-control {{ $errors->has('investment_title') ? 'form-control-danger' : '' }}"
                                                name="investment_title" type="text"
                                                value="{{ old('investment_title') }}" placeholder="Enter investment title">      
                                            @error('investment_title')
                                                <div class="col-form-alert-label">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3 {{ $errors->has('investment_detail') ? 'has-danger' : '' }}">
                                            <label class="col-form-label">Investment Detail*</label>
                                            <textarea
                                            class="form-control {{ $errors->has('investment_detail') ? 'form-control-danger' : ''}}"
                                            name="investment_detail" type="message"
                                                placeholder="Enter investment detail">{{ old('investment_detail') }}</textarea>  
                                            @error('investment_detail')
                                                <div class="col-form-alert-label">
                                                {{$message}}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer"> <button type="submit" class="btn btn-primary">{{ $common['button'] }}</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

