@include('includes.header')
<form name="SubmitForm" method="post" action="/order/{{ (! empty($edit)?$edit->id . '/':'') }}">
  @csrf
  @if(! empty($edit))
  @method('PUT')
  @endif
  <div class="col-12">
    <div class="row">
      <div class="col-7">
        <div class="row">
          <div class="col-12">
            <div class="card card-custom gutter-b">
              <div class="card-header border-0">
                <h3 class="card-title font-weight-bolder text-dark">Order Form</h3>
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
                    <label>เลขที่อ้างอิง</label>
                    <input type="text" name="reference" value="{{ (! empty($edit)?$edit->reference:'') }}" required>
                  </div>
                  <div class="col-6">
                    <label>ลูกค้า</label>
                    <select name="cust_id" required>
                      <option value="">เลือก...</option>
                      @foreach ($customers as $customer)
                      <option value="{{ $customer->id }}" {{ (! empty($edit) && $customer->id == $edit->cust_id)?'selected':'' }}>{{ $customer->name }} - {{ $customer->email }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-12">
                    <label>วิธีจัดส่ง</label>
                    <select name="ship_id" required>
                      <option value="">เลือก...</option>
                      @foreach ($shippings as $shipping)
                      <option value="{{ $shipping->id }}" {{ (! empty($edit) && $shipping->id == $edit->ship_id)?'selected':'' }}>{{ $shipping->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-12">
                    <label>เลือกวิธีการชำระเงิน</label>
                    <div class="btn-group btn-group-toggle select-payment">
                      @php ($checked = "")
                      @php ($active = "")
                      @if(empty($edit))
                      @php ($checked = "checked")
                      @php ($active = "active")
                      @endif
                      <label class="btn btn-secondary {{ (! empty($edit) && $edit->payment == 'full')?'active':$active }}">
                        <input type="radio" name="payment" value="full" {{ (! empty($edit) && $edit->payment == 'full')?'checked':$checked }}>เต็มจำนวน
                      </label>
                      <label class="btn btn-secondary {{ (! empty($edit) && $edit->payment == 'separate')?'active':'' }}">
                        <input type="radio" name="payment" value="separate" {{ (! empty($edit) && $edit->payment == 'separate')?'checked':'' }}>แยกชำระ
                      </label>
                      <label class="btn btn-secondary {{ (! empty($edit) && $edit->payment == 'installment')?'active':'' }}">
                        <input type="radio" name="payment" value="installment" {{ (! empty($edit) && $edit->payment == 'installment')?'checked':'' }}>ผ่อนชำระ
                      </label>
                    </div>
                  </div>
                  <div class="col-12">
                    <label>หมายเหตุ</label>
                    <input type="text" name="remark" value="{{ (! empty($edit)?$edit->remark:'') }}">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-5">
        <div class="row" style="position: sticky; top: 90px;">
          <div class="orderform card card-custom gutter-b" style="width: 85%;">
            <div class="card-header border-0">
              <h3 class="card-title font-weight-bolder text-dark">Order Detail</h3>
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
                @if( empty($edit))
                <div class="detail row" data-object="1">
                  <div class="col-8">
                    <label>สินค้า</label>
                    <select name="detail[0][product_detail_id]" required>
                      <option value="">เลือก...</option>
                      @foreach ($products as $product)
                      <optgroup label="{{ $product->brand . ' ' . $product->name_en }}">
                        @foreach ($product->detail as $detail)
                        <option value="{{ $detail->id }}">{{ $detail->barcode . ' - ' . $product->brand . ' '. $product->name_en }}</option>
                        @endforeach
                      <optgroup>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-4">
                    <label>จำนวน</label>
                    <input type="number" name="detail[0][amount]" value="" min="1" required>
                  </div>
                  <span class="add"></span>
                  <span class="remove"></span>
                </div>
                @else
                @php ($i = 0)
                @foreach ($order_details as $key => $order_detail)
                <div class="row detail" data-object="{{ $i+1 }}">
                  <input type="hidden" name="detail[{{ $i }}][id]" value="{{ $order_detail->id }}">
                  <div class="col-8">
                    <label>สินค้า</label>
                    <select name="detail[{{ $i }}][product_detail_id]" required>
                      <option value="">เลือก...</option>
                      @foreach ($products as $product)
                      <optgroup label="{{ $product->brand . ' ' . $product->name_en }}">
                        @foreach ($product->detail as $detail)
                        <option value="{{ $detail->id }}" {{ ($detail->id == $order_detail->product_detail_id)?'selected':'' }}>{{ $detail->barcode . ' - ' . $product->brand . ' '. $product->name_en }}</option>
                        @endforeach
                      <optgroup>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-4">
                    <label>จำนวน</label>
                    <input type="number" name="detail[{{ $i }}][amount]" value="{{ $order_detail->amount }}" min="1" required>
                  </div>
                  <span class="add"></span>
                  <span class="remove"></span>
                </div>
                @php ($i++)
                @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@include('includes.footer')