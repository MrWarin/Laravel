@include('includes.header')
<div class="container">
  <form name="SubmitForm" method="post" action="/member/login/">
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
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" value="1">
          Remember Me
          <input type="submit" value="Sign In">
        </div>
      </div>
    </div>
  </form>
</div>
@include('includes.footer')