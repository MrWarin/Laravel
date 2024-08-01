@include('includes.header')
<div class="col-12">
  <div class="card card-custom gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-dark">Administrator</h3>
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
            <a href="/log/create/"><button class="btn btn-primary"><i class="fas fa-plus-circle"></i></button></a>
            <button id="exportExcel" class="btn btn-primary"><i class="fas fa-file-export"></i></button>
          </div>
        </div>
      </form>
      <div class="pagination mb-2">
        @for ($i = 1; $i <= $page_num; $i++)
        <a href="/log/{{ $i }}/page/"><button type="button" class="btn btn-secondary mr-2 {{ ($i == $page?'active':'') }}">{{ $i }}</button></a>
        @endfor
      </div>
      <div id="table">
        <table>
          <tr>
            <th class="text-center" width="10%">ไอพีแอดเดรส</th>
            <th class="text-center" width="20%">รายละเอียด</th>
            <th class="text-center" width="10%">พาร์ธ</th>
            <th class="text-center" width="10%">ผู้ทำรายการ</th>
            <th class="text-center" width="15%">วันที่</th>
          </tr>
          @foreach($logs as $i => $log)
          <tr class="{{ ($i % 2 == 0)?'odd':'' }}">
            <td class="text-center">{{ $log->ipaddress }}</td>
            <td class="text-center">{{ $log->description }}</td>
            <td class="text-center">{{ $log->path }}</td>
            <td class="text-center">{{ $log->created_by }}</td>
            <td class="text-center">{{ $log->created_at }}</td>
          </tr>
          @endforeach
        </table>
      </div>
      <div class="pagination mt-2">
        @for ($i = 1; $i <= $page_num; $i++)
        <a href="/log/{{ $i }}/page/"><button type="button" class="btn btn-secondary mr-2 {{ ($i == $page?'active':'') }}">{{ $i }}</button></a>
        @endfor
      </div>
    </div>
  </div>
</div>
@include('includes.footer')