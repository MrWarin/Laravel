@include('includes.header')
<div class="col-12">
  <form name="SubmitForm" method="post" action="/user/{{ (! empty($edit)?$edit->id . '/':'') }}" enctype="multipart/form-data">
    @csrf
    @if(! empty($edit))
    @method('PUT')
    @endif
    <div class="row">
      <div class="col-8">
        <div class="card card-custom gutter-b">
          <div class="card-header border-0">
            <h3 class="card-title font-weight-bolder text-dark">User Form</h3>
            <div class="card-toolbar">
              <div class="dropdown dropdown-inline">
                <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
                  <i class="fas fa-ellipsis-v"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <label>ชื่อ</label>
                <input type="text" name="name" value="{{ (! empty($edit)?$edit->name:'') }}" required>
              </div>
              <div class="col-6">
                <label>อีเมล</label>
                <input type="text" name="email" value="{{ (! empty($edit)?$edit->email:'') }}" required>
              </div>
              <!-- <div class="col-6">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="number" name="telephone" value="{{ (! empty($edit)?$edit->telephone:'') }}" required>
                  </div> -->
              @if(empty($edit))
              <div class="col-6">
                <label>รหัสผ่าน</label>
                <input type="password" name="password" value="" required>
              </div>
              <div class="col-6">
                <label>ยืนยันรหัสผ่าน</label>
                <input type="password" name="confirm_password" value="" required>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="row" style="position: sticky; top: 90px;">
          <div class="col-12">
            <div class="card card-custom gutter-b">
              <div class="card-header border-0">
                <h3 class="card-title font-weight-bolder text-dark">Profile Picture</h3>
                <div class="card-toolbar">
                  <div class="dropdown dropdown-inline">
                    <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    @if(! empty($edit->image))
                    <img src="{{ asset('/images/profiles/users/' . $edit->id . '/' . $edit->image) }}" style="height: 80px; margin-bottom: 10px">
                    @else
                    <img src="{{ asset('/images/profiles/default/profile.jpg') }}" style="height: 80px; margin-bottom: 10px">
                    @endif
                  </div>
                  <div class="col-12">
                    <input type="file" name="profile">
                  </div>
                </div>
              </div>
            </div>
          </div>
  </form>
  @if (! empty($edit))
  <div class="col-12">
    <div class="card card-custom gutter-b">
      <div class="card-header border-0">
        <h3 class="card-title font-weight-bolder text-dark">Password Changing</h3>
        <div class="card-toolbar">
          <div class="dropdown dropdown-inline">
            <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
              <i class="fas fa-ellipsis-v"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form method="post" action="/user/{{ $edit->id }}/reset-password/">
          @csrf
          <div class="row">
            <div class="col-12">
              <label>รหัสผ่านเดิม</label>
              <input type="password" name="old_password" value="" required>
            </div>
            <div class="col-12">
              <label>รหัสผ่านใหม่</label>
              <input type="password" name="new_password" value="" required>
            </div>
            <div class="col-12">
              <label>ยืนยันรหัสผ่านใหม่</label>
              <input type="password" name="confirm_password" value="" required>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
@endif
@include('includes.footer')