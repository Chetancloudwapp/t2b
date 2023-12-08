@extends('admin::admin.layout.layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Setting</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
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
                        <div class="card-body">
                            @if(Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            @if(Session::has('error_message'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> {{ Session::get('error_message')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif
                            <form action="{{ url('admin/change_password')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    {{-- <div class="form-group"> 
                                        <label for="admin_email">Email</label> 
                                        <input class="form-control" id="admin_email" value="{{ Auth::guard('admin')->user()->email}}" readonly=""> 
                                    </div> --}}
                                    <div class="form-group"> 
                                        <label for="current_pwd">Old Password</label> 
                                        <input type="password" class="form-control" id="current_pwd" name="current_pwd" placeholder="Enter old password"><span id="vefiryCurrentPwd"></span>
                                    </div>
                                    <div class="form-group"> 
                                        <label for="new_pwd">New Password</label> 
                                        <input type="password" class="form-control" id="new_pwd" name="new_pwd" placeholder="Enter new password"> 
                                    </div>
                                    <div class="form-group"> 
                                        <label for="confirm_pwd">Confirm Password</label> 
                                        <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd" placeholder="Enter confirm password"> 
                                    </div>
                                </div>
                                <div class="card-footer"> <button type="submit" class="btn btn-primary">Update Password</button> </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    // Check admin password is correct or not
    $("#current_pwd").keyup(function(){
        var current_pwd = $("#current_pwd").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type:'post',
            url:"{{ url('admin/check_current_password') }}",
            data:{"current_pwd":current_pwd},
            success:function(resp){
                if(resp == "false"){
                    $("#vefiryCurrentPwd").html("Old Password is Incorrect !");
                }else if(resp == "true"){
                    $("#vefiryCurrentPwd").html("Old Password is correct !");
                }
            },error:function(){
                alert('Error');
            }
        })
    });
});
</script>
@endsection

