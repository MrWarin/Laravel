<div class="container">
  <div class="chatbox-wrapper hidden">
    <div class="chatbox">
      <div class="header justify-content-between">
        <div class="profile">
          <img src="{{ asset('images/profiles/users/default/profile.jpg') }}">
          <div class="name"></div>
        </div>
        <div class="action">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="body">
        <div class="message-box"></div>
      </div>
      <div class="footer">
        <input id="txtTo" type="hidden" name="txtTo" />
        <input id="txtMessage" type="text" name="txtMessage" placeholder="Enter Message" />
        <button id="btnSend" class="btn btn-violet"><i class="fas fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
  <div class="chatbox-wrapper">
    <div class="listbox">
      <div class="header justify-content-between">
        Online Users
        <div class="action">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="list"></div>
    </div>
  </div>
</div>