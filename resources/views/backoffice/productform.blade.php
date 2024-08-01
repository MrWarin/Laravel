@include('includes.header')
<div class="orderform" style="width: 95%;">
  <form name="SubmitForm" method="post" action="/product/{{ (! empty($edit)?$edit->id . '/':'') }}" enctype="multipart/form-data">
    @csrf
    @if(! empty($edit))
    @method('PUT')
    @endif
    <div class="col-12">
      <div class="card card-custom gutter-b">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bolder text-dark">General</h3>
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
              <label>ชื่อสินค้า (ภาษาไทย)</label>
              <input type="text" name="name_th" value="{{ (! empty($edit)?$edit->name_th:'') }}" required>
            </div>
            <div class="col-6">
              <label>ชื่อสินค้า (ภาษาอังกฤษ)</label>
              <input type="text" name="name_en" value="{{ (! empty($edit)?$edit->name_en:'') }}">
            </div>
            <div class="col-6">
              <label>แบรนด์</label>
              <select name="brand_id" required>
                <option value="">เลือก..</option>
                @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ (!empty($edit) && $edit->brand_id == $brand->id?'selected':'') }}>{{ $brand->name_en }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label>หมวดหมู่</label>
              <select name="cat_id" required>
                <option value="">เลือก..</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ (!empty($edit) && $edit->cat_id == $category->id?'selected':'') }}>{{ $category->name_th }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label>รายละเอียดแบบย่อ</label>
              <input type="text" name="short_description" value="{{ (! empty($edit)?$edit->short_description:'') }}">
            </div>
            <div class="col-12">
              <label>รายละเอียดสินค้า</label>
              <textarea rows="8" name="long_description">{{ (! empty($edit)?$edit->long_description:'') }}</textarea>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card card-custom gutter-b">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bolder text-dark">Attribute</h3>
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
            <div class="productlist col-12">
              <div class="detail row" data-object="1">
                <!-- <div class="col-3">
                  <label>ชื่อ</label>
                  <select name="attribute[0][name]">
                    <option value="">เลือก..</option>
                    @foreach ($attribute_group as $value)
                    <option value="{{ $value->id }}">{{ $value->name_th }}</option>
                    @endforeach
                  </select>
                </div> -->
                <div class="col-12">
                  <label>ตัวเลือก</label>
                  <select name="attribute[0][value]" class="select2" multiple="multiple">
                    @if(! empty($edit))
                    @foreach ($edit->detail as $detail)
                    <option value="{{ $detail->attribute_id }}" selected>{{ $detail->attribute_name }}</option>
                    @endforeach
                    @endif
                  </select>
                </div>
                <span class="add" data-target="productlist"></span>
                <span class="remove" data-target="productlist"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card card-custom gutter-b">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bolder text-dark">Price</h3>
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
              <div class="orderlist price">
                @if(empty($edit))
                <div class="detail row" data-object="1">
                  <div class="col-3">
                    <label>ตัวเลือกสินค้า</label>
                    <input type="text" class="attr_data" value="" disabled>
                    <input type="hidden" class="attr_val" name="detail[0][attr_id]" value="">
                  </div>
                  <div class="col-3">
                    <label>รหัสสินค้า</label>
                    <input type="text" name="detail[0][barcode]">
                  </div>
                  <div class="col-3">
                    <label>ราคา</label>
                    <input type="number" name="detail[0][price]">
                  </div>
                  <div class="col-3">
                    <label>คลัง</label>
                    <input type="number" name="detail[0][amount]" value="" min="0">
                  </div>
                  <!-- <span class="add"></span>
                      <span class="remove"></span> -->
                </div>
                @else
                @foreach ($edit->detail as $i => $detail)
                <div class="detail row" data-object="{{ $i + 1 }}">
                  <input type="hidden" name="detail[{{ $i }}][id]" value="{{ $detail->id }}">
                  <div class="col-3">
                    <label>ตัวเลือกสินค้า</label>
                    <input type="text" class="attr_data" value="{{ $detail->attribute_name }}" disabled>
                    <input type="hidden" class="attr_val" name="detail[{{ $i }}][attr_id]" value="{{ $detail->attribute_id }}">
                  </div>
                  <div class="col-3">
                    <label>รหัสสินค้า</label>
                    <input type="text" name="detail[{{ $i }}][barcode]" value="{{ $detail->barcode }}">
                  </div>
                  <div class="col-3">
                    <label>ราคา</label>
                    <input type="number" name="detail[{{ $i }}][price]" value="{{ $detail->price }}">
                  </div>
                  <div class="col-3">
                    <label>คลัง</label>
                    <input type="number" name="detail[{{ $i }}][amount]" value="{{ $detail->amount }}" min="0">
                  </div>
                  <!-- <span class="add"></span>
                      <span class="remove"></span> -->
                </div>
                @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12">
      <div class="card card-custom gutter-b">
        <div class="card-header border-0">
          <h3 class="card-title font-weight-bolder text-dark">Picture</h3>
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
            <div class="orderlist col-12">
              @if (empty($edit))
              <div class="detail row" data-object="1">
                @for ($i = 0; $i < 6; $i++) <div class="image-upload col-2">
                  <label for="file-input-0{{ $i }}">
                    <img id="image-input-0{{ $i }}" class="image-input" src="{{ asset('images/no-image.jpg') }}">
                  </label>
                  <input type="file" id="file-input-0{{ $i }}" class="file-input" name="image[0][{{ $i }}][name]" data-preview="image-input-0{{ $i }}">
              </div>
              @endfor
              <span class="add"></span>
              <span class="remove"></span>
            </div>
            @else
            @foreach($edit->detail as $i => $detail)
            <div class="detail row" data-object="{{ $i + 1 }}">
              @foreach($detail->image as $j => $image)
              <div class="image-upload col-2">
                <label for="file-input-{{ $i . $j }}">
                  <img id="image-input-{{ $i . $j }}" class="image-input" src="{{ asset('images/products/' . $image['product_id'] . '/' . $image['name']) }}">
                </label>
                <input type="file" id="file-input-{{ $i . $j }}" class="file-input" name="image[{{$i }}][{{ $j }}][name]" data-preview="image-input-{{ $i . $j }}">
              </div>
              @endforeach
              @for ($i = count($detail->image); $i < 6; $i++) <div class="image-upload col-2">
                <label for="file-input-{{ $i . $j }}">
                  <img id="image-input-{{ $i . $j }}" class="image-input" src="{{ asset('images/no-image.jpg') }}">
                </label>
                <input type="file" id="file-input-{{ $i . $j }}" class="file-input" name="image[{{ $i }}][{{ $j }}][name]" data-preview="image-input-{{ $i . $j }}">
            </div>
            @endfor
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
<div class="col-12">
  <div class="card card-custom gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-dark">Shipment</h3>
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
        <div class="col-3">
          <label>น้ำหนัก (กิโลกรัม)</label>
          <input type="number" name="weight" step="0.1" value="{{ (!empty($edit)?$edit->weight:'') }}">
        </div>
        <div class="col-3">
          <label>ความกว้าง (เมตร)</label>
          <input type="number" name="length" step="0.1" value="{{ (!empty($edit)?$edit->length:'') }}">
        </div>
        <div class="col-3">
          <label>ความสูง (เมตร)</label>
          <input type="number" name="width" step="0.1" value="{{ (!empty($edit)?$edit->width:'') }}">
        </div>
        <div class="col-3">
          <label>ความยาว (เมตร)</label>
          <input type="number" name="depth" step="0.1" value="{{ (!empty($edit)?$edit->depth:'') }}">
        </div>
      </div>
    </div>
  </div>
</div>
</form>
</div>
@include('includes.footer')