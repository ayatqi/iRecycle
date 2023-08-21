@extends('admin.admin_dashboard')
@section('admin')
<div class="col-md-8 col-xl-8 middle-wrapper">
<div class="row">
    <div class="card">
  <div class="card-body">

                    <h6 class="card-title">Edit Profile</h6>

        <form class="forms-sample" method="POST" action="{{route('admin.update.user', ['id' => $user->id])}}" enctype="multipart/form-data">
                @csrf

                        <div class="mb-3">
                            <label for="exampleInputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name"  name="name" autocomplete="off" placeholder="Name" value="{{$user->name}}">
                        </div>


                        <div class="mb-3">
                            <label for="exampleInputUsername1" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" autocomplete="off" placeholder="Username" value="{{$user->username}}">
                        </div>


                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$user->email}}">
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" name="photo" id="photo" placeholder="Photo">
                            <img class="wd-100 rounded-circle" 
                            src="{{ !(empty($user->photo)) ? url('upload/admin_images/' . $user->photo) : url('upload/no_image.jpg')}}" 
                            alt="profile">
                        </div>

                        <div class="mb-3">
                            <label for="phonenumber" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number" value="{{$user->phone}}">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputAddress" class="form-label">Address</label>
                            <input type="address" class="form-control" id="address" name="address" autocomplete="off" placeholder="Address" value="{{$user->address}}">
                        </div>



                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">
                                Remember me
                            </label>
                        </div>


                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <button class="btn btn-secondary">Cancel</button>
        </form>

  </div>
</div>
<!-- middle wrapper end -->
<!-- right wrapper start -->
<!-- right wrapper end -->
</div>

</div>
@endsection
