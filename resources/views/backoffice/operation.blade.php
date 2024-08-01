@include('includes.header')
<div class="col-12">
  <form name="SubmitForm" method="post" action="{{ (! empty($edit)?'/operation/' . $edit->id . '/ ':'') }}">
    @csrf
    @if(! empty($edit))
    @method('PUT')
    @endif
    <div class="row">
      <div class="col-8">
        <div class="card card-custom gutter-b">
          <div class="card-header border-0">
            <h3 class="card-title font-weight-bolder text-dark">Opertation Add</h3>
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
                <label>ชื่อการปฏิบัติงาน</label>
                <input type="text" name="name" value="" required>
              </div>
              <div class="col-12">
                <label>ประเภท</label>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="type1" value="1" checked>
                <label class="form-check-label" for="type1">Development</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="type2" value="2">
                <label class="form-check-label" for="type2">Test</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="type3" value="3">
                <label class="form-check-label" for="type3">Document</label>
                </div>
              </div>
              <div class="col-12">
                <label>วันที่เริ่มปฏิบัติงาน</label>
                <input type="date" name="start" value="" required>
              </div>
              <div class="col-12">
                <label>วันที่เสร็จสิ้น</label>
                <input type="date" name="end" value="" required>
              </div>
              <div class="col-12">
                <label>สถานะ</label>
                <div class="col-12">
                <label>ประเภท</label>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status1" value="1" checked>
                <label class="form-check-label" for="status1">In Progress</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status2" value="2">
                <label class="form-check-label" for="status2">Finished</label>
                </div>
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status3" value="3">
                <label class="form-check-label" for="status3">Cancelled</label>
                </div>
              </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-success">Send</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="col-12">
  <div class="card card-custom gutter-b">
    <div class="card-header border-0">
      <h3 class="card-title font-weight-bolder text-dark">Operation</h3>
      <div class="card-toolbar">
        <div class="dropdown dropdown-inline">
          <a class="btn btn-clean btn-hover-light-primary btn-sm btn-icon">
            <i class="fas fa-ellipsis-v"></i>
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div id="table">
        <table>
          <tr>
            <th class="text-center" width="10%">ชื่อการปฏิบัติงาน</th>
            <th class="text-center" width="20%">ประเภท</th>
            <th class="text-center" width="10%">วันที่เริ่ม</th>
            <th class="text-center" width="10%">วันที่เสร็จสิ้น</th>
            <th class="text-center" width="10%">สถานะ</th>
            <th class="text-center" width="15%">วันที่สร้าง</th>
            <th class="text-center" width="15%">วันที่แก้ไข</th>
            <th width="15%"></th>
          </tr>
          @foreach($operations as $i => $operation)
          <tr class="{{ ($i % 2 == 0)?'odd':'' }}">
            <td class="text-center">{{ $operation->name }}</td>
            <td class="text-center">{{ $operation->type }}</td>
            <td class="text-center">{{ $operation->status }}</td>
            <td class="text-center">{{ $operation->start }}</td>
            <td class="text-center">{{ $operation->end }}</td>
            <td class="text-center">{{ $operation->created_at->format('d/m/Y H:i A') }}</td>
            <td class="text-center">{{ $operation->updated_at->format('d/m/Y H:i A') }}</td>
            <td class="text-right">
              <a href="/operation/{{ $operation->id }}"><button class="btn btn-success" title="รายละเอียด"><i class="fa fa-eye"></i></button>
                <a href="/operation/{{ $operation->id }}/edit/"><button class="btn btn-success" title="แก้ไข"><i class="fa fa-edit"></i></button></a>
                <form class="d-inline-block" action="/operation/{{ $operation->id }}/" method="post">
                  @method('DELETE')
                  @csrf
                  <button class="btn btn-danger" type="submit" title="ลบ"><i class="fa fa-trash"></i></button>
                </form>
            </td>
          </tr>
          @endforeach
        </table>
      </div>
    </div>
  </div>
</div>
@include('includes.footer')