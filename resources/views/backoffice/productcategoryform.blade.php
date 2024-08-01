@include('includes.header')
<div class="col-12">
  <form name="SubmitForm" method="post" action="/product-category/{{ (! empty($edit)?$edit->id . '/':'') }}" enctype="multipart/form-data">
    @csrf
    @if(! empty($edit))
    @method('PUT')
    @endif
    <div class="row">
      <div class="col-6">
        <div class="card card-custom gutter-b">
          <div class="card-header border-0">
            <h3 class="card-title font-weight-bolder text-dark">Category Form</h3>
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
                <label>ชื่อหมวดหมู่ (ภาษาไทย)</label>
                <input type="text" name="name_th" value="{{ (! empty($edit)?$edit->name_th:'') }}" required>
              </div>
              <div class="col-6">
                <label>ชื่อหมวดหมู่ (ภาษาอังกฤษ)</label>
                <input type="text" name="name_en" value="{{ (! empty($edit)?$edit->name_en:'') }}" required>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="card card-custom gutter-b">
          <div class="card-header border-0">
            <h3 class="card-title font-weight-bolder text-dark">Category Parent</h3>
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
                <label></label>
                <select name="cat_parent_id">
                  <option value="">เลือก...</option>
                  @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name_th }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@include('includes.footer')