@extends('admin::admin.layout.layout')
@section('content')
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
                            <form action="{{ url('admin/events/edit/'.$events['id']) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="id" value="{{$events['id']}}">
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('name') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Name')}}<span class="mandatory cls" style="color:red; font-size:15px">*</span></label>
                                        <input
                                            class="form-control {{ $errors->has('name') ? 'form-control-danger' : '' }}"
                                            name="name" type="text"
                                            value="{{ old('name', $events['name']) }}" placeholder="Enter name">      
                                        @error('name')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('eventdate') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('EventDate')}}
                                      
                                        </label>
                                        <input
                                            class="form-control {{ $errors->has('eventdate') ? 'form-control-danger' : '' }}"
                                            name="eventdate" type="date"

                                            value="{{ date("Y-m-d",strtotime($events['eventdate'])) }}" placeholder="Enter name">      
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
                                            {{-- @foreach($events['images'] as $value)
                                            <li>
                                                @if($value['product_id'] == $products['id'])
                                                    <a target="_blank" href="{{ asset('uploads/events/galleryImages/'. $value['image'])}}"><img src="{{ $value['image'] != '' ? asset('uploads/products/galleryImages/small/'. $value['image']) : asset('assets/upload//placeholder.png') }}"  class="user-img img-css" id="image_1">
                                                    </a>&nbsp;
                                                    <a href="{{ url('admin/product/deleteImage/'. $value['id'])}}" title="Delete Image" name="product" title="Delete Product Image"> <i class="fa-solid fa-trash" style="color: red;"></i> 
                                                    </a>
                                                @endif  
                                            </li>
                                            @endforeach --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3 {{ $errors->has('description') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">Description</label>
                                        <textarea
                                           class="form-control summernote {{ $errors->has('description') ? 'form-control-danger' : ''}}"
                                           name="description" type="message"
                                            placeholder="Enter Description">{{ old('description',  $events['description']) }}</textarea>   
                                        @error('description')
                                            <div class="col-form-alert-label">
                                            {{$message}}
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
                                {{-- <div class="col-md-6">
                                    <div class="form-group mb-3 {{ $errors->has('status_reason') ? 'has-danger' : '' }}">
                                        <label class="col-form-label">{{('Status Reason')}}<span class="mandatory cls" style="color:red; font-size:15px">*</span></label>
                                        <input
                                            class="form-control {{ $errors->has('status_reason') ? 'form-control-danger' : '' }}"
                                            name="status_reason" type="text"
                                            value="{{ old('status_reason', $events['status_reason']) }}" placeholder="Enter Status Reason">      
                                        @error('status_reason')
                                            <div class="col-form-alert-label">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>
                            <div class="card-footer"> <button type="submit" class="btn btn-primary">Submit</button> </div>
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

