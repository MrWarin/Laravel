@include('includes.header')
<div class="col-12">
  <form name="SubmitForm" method="post" action="/customer/{{ (! empty($edit)?$edit->id . '/':'') }}" enctype="multipart/form-data">
    @csrf
    @if(! empty($edit))
    @method('PUT')
    @endif
    <div class="row">
      <div class="col-8">
        <div class="row">
          <div class="col-12">
            <div class="card card-custom gutter-b">
              <div class="card-header border-0">
                <h3 class="card-title font-weight-bolder text-dark">Customer Form</h3>
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
                    <input type="text" name="name" value="{{ (! empty($edit)?$edit->name:old('name')) }}" required>
                  </div>
                  <div class="col-6">
                    <label>อีเมล</label>
                    <input type="text" name="email" value="{{ (! empty($edit)?$edit->email:old('email')) }}" required>
                  </div>
                  <div class="col-6">
                    <label>เบอร์โทรศัพท์</label>
                    <input type="number" name="telephone" value="{{ (! empty($edit)?$edit->telephone:old('telephone')) }}" required>
                  </div>
                  <div class="col-6">
                    <label>วันเกิด</label>
                    <input type="date" name="birthdate" value="{{ (! empty($edit)?$edit->birthdate:old('birthdate')) }}">
                  </div>
                  <div class="col-6">
                    <label>เพศ</label>
                    <select name="gender">
                      <option value="">เลือก...</option>
                      <option value="male" {{ (! empty($edit) && $edit->gender == 'male'?'selected':'') }}>ชาย</option>
                      <option value="female" {{ (! empty($edit) && $edit->gender == 'female'?'selected':'') }}>หญิง</option>
                      <option value="undefined" {{ (! empty($edit) && $edit->gender == 'undefined'?'selected':'') }}>ไม่ระบุ</option>
                    </select>
                  </div>
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
          <div class="col-12">
            <div class="orderform card card-custom gutter-b">
              <div class="card-header border-0">
                <h3 class="card-title font-weight-bolder text-dark">Address</h3>
                <div class="card-toolbar">
                  <div class="dropdown dropdown-inline">
                    <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="orderlist">
                  @if (empty($addresses))
                  <div class="detail row" data-object="1">
                    <div class="col-8">
                      <label>ชื่อผู้รับ</label>
                      <input type="text" name="address[0][receiver]" value="">
                    </div>
                    <div class="col-4">
                      <label>เบอร์โทรศัพท์</label>
                      <input type="number" name="address[0][telephone]" value="">
                    </div>
                    <div class="col-8">
                      <label>บ้านเลขที่</label>
                      <input type="text" name="address[0][address]" value="">
                    </div>
                    <div class="col-4">
                      <label>จังหวัด</label>
                      <select name="address[0][province_id]">
                        <option value="">เลือก..</option>
                        @foreach ($provinces as $province)
                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-4">
                      <label>อำเภอ/เขต</label>
                      <select name="address[0][district_id]">
                        <option value="">เลือก..</option>
                        @foreach ($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-4">
                      <label>ตำบล/แขวง</label>
                      <select name="address[0][subdistrict_id]">
                        <option value="">เลือก..</option>
                        @foreach ($subdistricts as $subdistrict)
                        <option value="{{ $subdistrict->id }}">{{ $subdistrict->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-4">
                      <label>รหัสไปรษณีย์</label>
                      <input type="number" name="address[0][zipcode_id]" value="">
                    </div>
                    <span class="add"></span>
                    <span class="remove"></span>
                  </div>
                  @else
                  @foreach ($addresses as $i => $address)
                  <div class="row detail" data-object="{{ $i+1 }}">
                    <input type="hidden" name="address[{{ $i }}][id]" value="{{ $address->id }}">
                    <div class="col-8">
                      <label>ชื่อผู้รับ</label>
                      <input type="text" name="address[{{ $i }}][receiver]" value="{{ $address->receiver }}">
                    </div>
                    <div class="col-4">
                      <label>เบอร์โทรศัพท์</label>
                      <input type="number" name="address[{{ $i }}][telephone]" value="{{ $address->telephone }}">
                    </div>
                    <div class="col-8">
                      <label>บ้านเลขที่</label>
                      <input type="text" name="address[{{ $i }}][address]" value="{{ $address->address }}">
                    </div>
                    <div class="col-4">
                      <label>จังหวัด</label>
                      <select name="address[{{ $i }}][province_id]">
                        <option value="">เลือก..</option>
                        @foreach ($provinces as $province)
                        <option value="{{ $province->id }}" {{ ($province->id == $address->province_id)?'selected':'' }}>{{ $province->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-4">
                      <label>อำเภอ/เขต</label>
                      <select name="address[{{ $i }}][district_id]">
                        <option value="">เลือก..</option>
                        @foreach ($districts as $district)
                        <option value="{{ $district->id }}" {{ ($district->id == $address->district_id)?'selected':'' }}>{{ $district->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-4">
                      <label>ตำบล/แขวง</label>
                      <select name="address[{{ $i }}][subdistrict_id]">
                        <option value="">เลือก..</option>
                        @foreach ($subdistricts as $subdistrict)
                        <option value="{{ $subdistrict->id }}" {{ ($subdistrict->id == $address->subdistrict_id)?'selected':'' }}>{{ $subdistrict->name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-4">
                      <label>รหัสไปรษณีย์</label>
                      <input type="number" name="address[{{ $i }}][zipcode_id]" value="">
                    </div>
                    <span class="add"></span>
                    <span class="remove"></span>
                  </div>
                  @endforeach
                  @endif
                </div>
              </div>
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
                    <img src="{{ asset('/images/profiles/customers/' . $edit->id . '/' . $edit->image) }}" style="height: 80px; margin-bottom: 10px">
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
      <div class="card-header border-0">เปลี่ยนรหัสผ่าน</div>
      <div class="card-body">
        <form method="post" action="/customer/{{ $edit->id }}/reset-password/">
          @csrf
          @if(! empty($edit))
          @method('PUT')
          @endif
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
              <button type="submit">ยืนยัน</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  @endif
</div>
</div>
</div>
</div>
@include('includes.footer')