@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$common['title']}}</h1>
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
                            @if(Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                            @endif
                            <h3 class="card-title nofloat"> <span>{{ $common['title'] }}</span>
                            	<span> <a href="{{ url('admin/photos/add') }}"> <button type="button" class="btn btn-block btn-primary"><i class="fa-solid fa-plus"></i>&nbsp;&nbsp;Add photos</button> </a> </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table id="categories" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Title</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($photos as $key => $photo)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            {{-- <td>
                                                <img class="tbl-img-css rounded-circle" width="50px"
                                                src="{{ asset('uploads/photos/'. $photo['banner_image']) }}">
                                            </td> --}}
                                            <td>{{ $photo->getTranslation('title','it') }}</td>
                                            <td class="text-center">
                                                {{-- <a href="javascript:;"> <i class="fa-solid fa-eye"></i> </a> --}}
                                                <a href="{{ url('admin/photos/edit/'.$photo['id']) }}"> <i class="fa-solid fa-pencil"></i></a>
                                                <a href="javascript:void(0)" record="photos/delete" record_id="{{ $photo['id'] }}" class="confirmDelete" name="photos" title="Delete photos Page"> <i class="fa-solid fa-trash" ></i> </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
       $(document).on('click', ".confirmDelete", function(){
           var record = $(this).attr('record');
           var record_id = $(this).attr('record_id');
           Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
                )

                // root = "{{ config('app.url') }}"
                window.location.href = "/admin/"+record+"/"+record_id;
            }
            });

       });
    });
</script>
@endsection

