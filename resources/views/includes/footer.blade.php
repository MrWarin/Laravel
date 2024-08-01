          @if (str_contains($view_name, 'form'))
          <div class="kt-header kt-grid__item kt-header--fixed kt-footer">
            <div class="col-12">
                <div style="float: right;">
                  <button id="submit" type="button" class="btn btn-success">Save</button>
                  <button type="button" class="btn btn-light" onclick="window.history.back();">Back</button>
                </div>
            </div>
          </div>
          @endif
        </section>
      </div>
    </div>
    <div id="chatbox"></div>
    <div id="particles-js"></div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- <script src="{{ asset('js/jquery.select2.js') }}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/particles.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
  </body>
</html>
