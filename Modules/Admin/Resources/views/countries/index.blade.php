@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Countries</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active">Countries</li>
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
                            <h3 class="card-title nofloat"> <span>Countries</span></h3>
                        </div>
                        <div class="card-body">
                            <table id="categories" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Flag</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        {{-- <th class="text-center">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($get_countries as $key => $countries)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $countries['emoji'] }}</td>
                                            <td>{{ $countries['name'] }}</td>
                                            <td>
                                                @if($countries['is_show'] == 1)
                                                <a class="updateCountryStatus" id="country-{{ $countries['id']}}" country_id="{{ $countries['id'] }}" href="javascript:;void(0)" style='color:#3f6ed3'><i class="fas fa-toggle-on" status="Active"></i>
                                                </a>
                                                @else
                                                <a class="updateCountryStatus" id="country-{{ $countries['id']}}" country_id="{{ $countries['id']}}" style="color:gray" href="javascript:;void(0)"><i class="fas fa-toggle-off" status="Inactive"></i>
                                                </a>
                                                @endif
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
        $(document).on("click", ".updateCountryStatus", function(){
        // here i is the children of link tag a and active-inactive status is our attribute
        var status = $(this).children("i").attr("status");
        // alert(status);
        var country_id = $(this).attr("country_id");
        // alert(country_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url : '/admin/update-country-status',
            data:{status:status, country_id:country_id},
            success:function(resp){
                if(resp['status'] == 0){
                    $("#country-"+country_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>");
                }else if(resp['status'] ==1){
                    $("#country-"+country_id).html("<i class='fas fa-toggle-on' style='color:#3f6ed3' status='Active'></i>");
                }
            },error:function(){
                alert('Error');
            }
        });
    });
    });
</script>


@endsection

