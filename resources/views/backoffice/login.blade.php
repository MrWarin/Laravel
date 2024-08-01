@include('includes.header')
<div class="card-body">
  <div class="col-4" style="margin: auto;">
    <form name="SubmitForm" method="post" action="/login/">
      @csrf
      <div class="row">
        <div class="col-12">
          <label>Email</label>
          <input type="text" name="email" value=""></input>
        </div>
        <div class="col-12">
          <label>Password</label>
          <input type="password" name="password" value=""></input>
        </div>
        <div class="col-12">
          <label class="kt-checkbox">
            <input class="form-check-input" type="checkbox" name="remember" value="1"><span style="margin-top: 7px;"></span>
          </label>
          <span style="padding-left: 25px;">Remember Me</span>
          <button type="submit" class="btn btn-violet">LOGIN</button>
        </div>
      </div>
    </form>
  </div>
</div>
@include('includes.footer')