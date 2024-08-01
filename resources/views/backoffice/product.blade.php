@include('includes.header')
<div class="col-12">
  <div class="card card-custom gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-dark">Product</h3>
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
            <a href="/product/create/"><button type="button" class="btn btn-primary"><i class="fas fa-plus-circle"></i></button></a>
            <button id="exportExcel" class="btn btn-primary"><i class="fas fa-file-export"></i></button>
          </div>
        </div>
      </form>
      <div class="pagination mb-2">
        @for ($i = 1; $i <= $page_num; $i++)
        <a href="/product/{{ $i }}/page/"><button type="button" class="btn btn-secondary mr-2 {{ ($i == $page?'active':'') }}">{{ $i }}</button></a>
        @endfor
      </div>
      <div id="table">
        <table>
          <tr>
            <th class="text-center" width="1%"><label class="kt-checkbox"><input type="checkbox" name="checkall"><span></span></label></th>
            <th class="text-center" width="10%">แบรนด์</th>
            <th class="text-center" width="20%">ชื่อสินค้า</th>
            <th class="text-center" width="10%">หมวดหมู่</th>
            <th class="text-center" width="10%">ราคา</th>
            <th class="text-center" width="10%">จำนวนทั้งหมด</th>
            <th class="text-center" width="15%">วันที่ลงสินค้า</th>
            <th width="15%"></th>
          </tr>
          @foreach($products as $i => $product)
          <tr class="{{ ($i % 2 == 0)?'odd':'' }}">
            <td><label class="kt-checkbox"><input type="checkbox" name="checkthis" data-id=""><span></span></label></td>
            <td class="text-center">{{ $product->brand }}</td>
            <td class="text-center">{{ $product->name_th }}</td>
            <td class="text-center">{{ $product->category }}</td>
            <td class="text-center">{{ number_format($product->price, 2) }}</td>
            <td class="text-center">{{ $product->stock }}</td>
            <td class="text-center">{{ $product->created_at->format('d/m/Y H:i A') }}</td>
            <td class="text-right">
              <a href="/product/{{ $product->id }}"><button class="btn btn-success" title="รายละเอียด"><i class="fa fa-eye"></i></button>
                <a href="/product/{{ $product->id }}/edit/"><button class="btn btn-success" title="แก้ไข"><i class="fa fa-edit"></i></button></a>
                <form class="d-inline-block" action="/product/{{ $product->id }}/" method="post">
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
        @for ($i = 1; $i <= $page_num; $i++)
        <a href="/product/{{ $i }}/page/"><button type="button" class="btn btn-secondary mr-2 {{ ($i == $page?'active':'') }}">{{ $i }}</button></a>
        @endfor
      </div>
    </div>
  </div>
</div>
@include('includes.footer')