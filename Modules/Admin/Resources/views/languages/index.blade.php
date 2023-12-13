@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Languages</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Languages</li>
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
                            <h3 class="card-title nofloat"> <span>Languages</span>
                            	<span> <a href="{{ url('admin/language/add') }}"> <button type="button" class="btn btn-block btn-primary">Add Languages</button> </a> </span>
                            </h3>
                        </div>
                        <div class="card-body">
                            <table id="categories" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($languages as $key => $language)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>
                                                <img class="tbl-img-css rounded-circle" width="50px"
                                                src="{{ asset('uploads/languages/'. $language['image']) }}">
                                            </td>
                                            <td>{{ $language['name'] }}</td>
                                            <td>
                                                @if($language['status'] == 'Active')
                                                   <span class="badge badge-pill badge-success">{{ $language['status']}}</span>
                                                @else
                                                   <span class="badge badge-pill badge-danger">{{ $language['status'] }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{-- <a href="javascript:;"> <i class="fa-solid fa-eye"></i> </a> --}}
                                                <a href="{{ url('admin/language/edit/'.$language['id']) }}"> <i class="fa-solid fa-pencil"></i></a>
                                                <a href="javascript:void(0)" record="language/delete" record_id="{{ $language['id'] }}" class="confirmDelete" name="Language" title="Delete Language Page"> <i class="fa-solid fa-trash" ></i> </a>
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
