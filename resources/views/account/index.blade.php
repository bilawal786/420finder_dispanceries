@extends('layouts.admin')

    @section('content')

        <!-- OVERVIEW -->
        <div class="panel panel-headline">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="panel-title">Account Settings</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                <div class="row">

                  <div class="col-md-12">
                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Select Your Directions</strong></h4>
                        </div>
                        <div class="col-md-6 text-center">

                          <button onclick="getLocation()" class="btn btn-info">Click to add your directions</button>

                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-12">
                          <h4><strong>Profile Picture</strong></h4>
                          <div>
                            @if($business->profile_picture == '')
                              <img src="{{ asset('images/placeholder.png') }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                            @else
                              <img src="{{ $business->profile_picture }}" class="img-thumbnail" style="width: 100px; height: 100px;">
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6 pb-5">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-12">
                          <form action="{{ route('updateprofilepicture') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                              <label for="">Profile Picture</label>
                              <input type="file" name="profile_picture" class="form-control" required="">
                            </div>
                            <div class="form-group">
                              <button class="btn btn-primary">Update</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>First Name</strong></h4>
                          <p class="text-black-50">{{ $business->first_name }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#firstname" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Last Name</strong></h4>
                          <p class="text-black-50">{{ $business->last_name }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#lastname" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Email</strong></h4>
                          <p class="text-black-50">{{ $business->email }}</p>
                        </div>

                        <div class="col-md-6 text-right">
                            <a data-toggle="modal" data-target="#email" class="cursor-pointer">Edit</a>
                        </div>

                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Phone</strong></h4>
                          <p class="text-black-50">{{ $business->phone_number }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#phonenumber" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Business Name</strong></h4>
                          <p class="text-black-50">{{ $business->business_name }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#businessname" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Business Type</strong></h4>
                          <p class="text-black-50">{{ $business->business_type }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Business Phone</strong></h4>
                          <p class="text-black-50">{{ $business->business_phone_number }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                            <a data-toggle="modal" data-target="#businessphonenumber" class="cursor-pointer">Edit</a>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Order Method</strong></h4>
                          <div class="row">
                            <div class="col-md-12">
                              <form action="{{ route('updateordermethod') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                  <label for="">Select</label>
                                  <select name="order_method" class="form-control">
                                    @if($business->order_online == 0)
                                      <option value="0">In Store Purchase</option>
                                      <option value="1">Order Online</option>
                                    @else
                                      <option value="1">Order Online</option>
                                      <option value="0">In Store Purchase</option>
                                    @endif

                                  </select>
                                </div>
                                <div class="form-group">
                                  <button class="btn btn-primary">Update</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Country</strong></h4>
                          <p class="text-black-50">{{ $business->country }}</p>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6">

                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Address Line 1</strong></h4>
                          <p class="text-black-50">{{ $business->address_line_1 }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#addressline1" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Address Line 2</strong></h4>
                          <p class="text-black-50">{{ $business->address_line_2 }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#addressline2" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>City</strong></h4>
                          <p class="text-black-50">{{ $business->city }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>State / Province</strong></h4>
                          <p class="text-black-50">{{ $business->state_province }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Postal Code</strong></h4>
                          <p class="text-black-50">{{ $business->postal_code }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Opening Time</strong></h4>
                          <p class="text-black-50">{{ date('h:i A', strtotime($business->opening_time)) }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#opening_time" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Closing Time</strong></h4>
                          <p class="text-black-50">{{ date('h:i A', strtotime($business->closing_time)) }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#closing_time" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Website</strong></h4>
                          <p class="text-black-50">{{ $business->website }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#website" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>Instagram</strong></h4>
                          <p class="text-black-50">{{ $business->instagram }}</p>
                        </div>
                        <div class="col-md-6 text-right">
                          <a data-toggle="modal" data-target="#instagram" class="cursor-pointer">Edit</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>License Number</strong></h4>
                          <p class="text-black-50">{{ $business->license_number }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>License Type</strong></h4>
                          <p class="text-black-50">{{ $business->license_type }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="card p-3 mt-3 shadow-sm bg-light">
                      <div class="row">
                        <div class="col-md-6">
                          <h4><strong>License Expiration</strong></h4>
                          <p class="text-black-50">{{ $business->license_expiration }}</p>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

<!-- First Name -->
<div class="modal fade" id="firstname" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Update First Name</strong></h4>
                    </div>
                    <div class="col-md-6 text-right pt-2 pe-3">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                </div>
                <form action="{{ route('savefirstname') }}" method="POST">
                    @csrf
                    <div class="row my-3">
                        <div class="col-md-12">
                            <div class="form-group">
                              <label for="">First Name</label>
                              <input type="text" name="first_name" value="{{ $business->first_name }}" placeholder="Enter First Name" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Last Name -->
<div class="modal fade" id="lastname" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('savelastname') }}" method="POST">
              @csrf
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <h4><strong>Update Last Name</strong></h4>
                  </div>
                  <div class="col-md-6 text-right pt-2 pe-3">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                </div>
              <div class="row my-3">
                <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Last Name</label>
                      <input type="text" name="last_name" value="{{ $business->last_name }}" placeholder="Enter Last Name" class="form-control" required="">
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block">
                        Save
                    </button>
                </div>
              </div>
              </div>
            </form>
        </div>
    </div>
</div>

<!-- Email -->
<div class="modal fade" id="email" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('update-email') }}" method="POST">
              @csrf
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <h4><strong>Update Email</strong></h4>
                  </div>
                  <div class="col-md-6 text-right pt-2 pe-3">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                </div>
              <div class="row my-3">
                <div class="col-md-12">
                    <div class="form-group">
                      <label for="">Email</label>
                      <input type="email" name="email" value="{{ $business->email }}" placeholder="Enter Email" class="form-control" required="">
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary btn-block">
                        Save
                    </button>
                </div>
              </div>
              </div>
            </form>
        </div>
    </div>
</div>

  <!-- Phone Number -->
  <div class="modal fade" id="phonenumber" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('savephonenumber') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Phone Number</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Phone Number</label>
                  <input id="editphonenumber" type="text" name="phone_number" value="{{ $business->phone_number }}" placeholder="Enter Phone Number" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary btn-block">
                Save
              </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Business Name -->
  <div class="modal fade" id="businessname" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updatebusinessname') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Business Name</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Business Name</label>
                  <input type="text" name="business_name" value="{{ $business->business_name }}" placeholder="Enter Last Name" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="businessphonenumber" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('update-business-phone') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Business Phone</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Business Phone Number</label>
                  <input type="text" name="business_phone_number" value="{{ $business->business_phone_number }}" placeholder="Enter Business Phone" class="form-control" required="" id="businessPhoneNumber">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Address Line 1 -->
  <div class="modal fade" id="addressline1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updateaddressone') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Address Line 1</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Address Line 1</label>
                  <input type="text" name="address_line_1" value="{{ $business->address_line_1 }}" placeholder="Enter Address Line 1" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary btn-block">Save</button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Address Line 2 -->
  <div class="modal fade" id="addressline2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updateaddresstwo') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Address Line 2</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Address Line 2</label>
                  <input type="text" name="address_line_2" value="{{ $business->address_line_2 }}" placeholder="Enter Address Line 2" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- website -->
  <div class="modal fade" id="website" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updatewebsiteurl') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Website URL</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Website URL</label>
                  <input id="editwebsite" type="text" name="website" value="{{ $business->website }}" placeholder="Enter website url" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- website -->
  <div class="modal fade" id="instagram" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updateinstagramurl') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Instagram URL</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Instagram URL</label>
                  <input id="editinstagram" type="text" name="instagram" value="{{ $business->instagram }}" placeholder="Enter Instagram url" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Opening Time -->
  <div class="modal fade" id="opening_time" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updateopeningtime') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Opening Time</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Select Opening Time</label>
                  <input id="editwebsite" type="time" name="opening_time" value="{{ $business->opening_time }}" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Closing Time -->
  <div class="modal fade" id="closing_time" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="{{ route('updateclosingtime') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <h4><strong>Update Closing Time</strong></h4>
              </div>
              <div class="col-md-6 text-right pt-2 pe-3">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>
            </div>
          <div class="row my-3">
            <div class="col-md-12">
                <div class="form-group">
                  <label for="">Select Closing Time</label>
                  <input id="editwebsite" type="time" name="closing_time" value="{{ $business->closing_time }}" class="form-control" required="">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-block">
                    Save
                </button>
            </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>

            </div>
        </div>



<!-- <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#firstname">
  Launch demo modal
</button> -->

<!-- Modal -->
<!-- <div class="modal fade" id="firstname" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
 -->


    @endsection