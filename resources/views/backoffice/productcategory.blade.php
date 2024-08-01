@include('includes.header')
<div class="col-12">
  <div class="card card-custom gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-dark">Product Category</h3>
      <div class="card-toolbar">
        <div class="dropdown dropdown-inline">
          <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
            <i class="fas fa-ellipsis-v"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <form id="FilterForm" method="get">
        <div class="row">
          <div class="col-4">
            <label>ค้นหา</label>
            <input type="text" name="filter[search]" title="ค้นหา เลขที่สั่งซื้อ, ชื่อ-นามสกุลลูกค้า, ที่อยู่ลูกค้า, รายการสินค้า, ราคารวม">
          </div>
          <div class="col-4">
            <label>จากวันที่</label>
            <input type="date" name="filter[from]" value="">
          </div>
          <div class="col-4">
            <label>ถึงวันที่</label>
            <input type="date" name="filter[to]" value="">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary" onclick="FilterForm.submit();"><i class="fas fa-search"></i></button>
            <a href="/product-category/create/"><button type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i></button></a>
            <button id="exportExcel" class="btn btn-primary"><i class="fas fa-file-export"></i></button>
          </div>
        </div>
      </form>
      <div class="pagination mb-2">
        @for ($i = 1; $i <= $page_num; $i++) <a href="/product-category/{{ $i }}/page/"><button type="button" class="btn btn-secondary mr-2 {{ ($i == $page?'active':'') }}">{{ $i }}</button></a>
          @endfor
      </div>
      <div id="table">
        <table>
          <tr>
            <th class="text-center" width="1%"><label class="kt-checkbox"><input type="checkbox" name="checkall"><span></span></label></th>
            <th class="text-center" width="10%">ชื่อหมวดหมู่ (ภาษาไทย)</th>
            <th class="text-center" width="10%">ชื่อหมวดหมู่ (ภาษาอังกฤษ)</th>
            <th class="text-center" width="10%">สินค้าในหมวดหมู่</th>
            <th width="15%"></th>
          </tr>
          @foreach($categories as $i => $category)
          <tr class="{{ ($i % 2 == 0)?'odd':'' }}">
            <td><label class="kt-checkbox"><input type="checkbox" name="checkthis" data-id=""><span></span></label></td>
            <td class="text-center">{{ $category->name_th }}</td>
            <td class="text-center">{{ $category->name_en }}</td>
            <td class="text-center">{{ $category->amount }}</td>
            <td class="text-right">
              <a href="/product-category/{{ $category->id }}/edit/"><button class="btn btn-success" title="แก้ไข"><i class="fa fa-edit"></i></button></a>
              <form class="d-inline-block" action="/product-category/{{ $category->id }}/" method="post">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger" type="submit" title="ลบ"><i class="fa fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </table>
      </div>
      <div class="pagination mt-2">
        @for ($i = 1; $i <= $page_num; $i++) <a href="/product-category/{{ $i }}/page/"><button type="button" class="btn btn-secondary mr-2 {{ ($i == $page?'active':'') }}">{{ $i }}</button></a>
          @endfor
      </div>
    </div>
  </div>
</div>
@include('includes.footer')