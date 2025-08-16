<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/toastr/toastr.min.css') }}">


<script>


  @if(Session::has('success'))
    toastr.remove();
    toastr.success("{{ Session::get('success') }}", '', {
        closeButton: true,
        timeOut: 20000,
        progressBar: true,
        newestOnTop: true
    }); 

    //toastr.success("{{ Session::get('success') }}");
  @endif


  @if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}", '', {
        closeButton: true,
        timeOut: 20000,
        progressBar: true,
        newestOnTop: true
    });
  @endif


  @if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}", '', {
        closeButton: true,
        timeOut: 20000,
        progressBar: true,
        newestOnTop: true
    });
  @endif


  @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}", '', {
        closeButton: true,
        timeOut: 20000,
        progressBar: true,
        newestOnTop: true
    });
  @endif


</script>