@include('includes.header')
<div class="col-12">
  <div class="row">
    <div class="col-4">
      <div class="card card-custom gutter-b">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bolder text-dark">Display</h3>
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
              <div class="col-12">
                <label>การแสดงผลข้อมูล</label>
                <input type="text" name="name" value="{{ (! empty($edit)?$edit->name:'') }}">
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-4">
      <div class="card card-custom gutter-b">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bolder text-dark">Pagination</h3>
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
              <div class="col-12">
                <label>การแสดงผลข้อมูล / หน้า</label>
                <input type="text" name="name" value="{{ (! empty($edit)?$edit->name:'') }}">
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-success">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@include('includes.footer')