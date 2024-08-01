@include('includes.header')
<div class="col-12">
  <div class="card card-custom gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-dark">Shipment Form</h3>
      <div class="card-toolbar">
        <div class="dropdown dropdown-inline">
          <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
            <i class="fas fa-ellipsis-v"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form name="SubmitForm" method="post" action="/shipping/{{ (! empty($edit)?$edit->id . '/':'') }}">
        @csrf
        @if(! empty($edit))
        @method('PUT')
        @endif
        <div class="row">
          <div class="col-6">
            <label>ชื่อ</label>
            <input type="text" name="name" value="{{ (! empty($edit)?$edit->name:'') }}" required>
          </div>
          <div class="col-6">
            <label>รายละเอียด</label>
            <input type="text" name="description" value="{{ (! empty($edit)?$edit->description:'') }}">
          </div>
          <div class="col-12">
            <button type="submit">ยืนยัน</button>
            <button id="clear" type="button">ล้างข้อมูล</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@include('includes.footer')