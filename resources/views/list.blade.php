@push('vuegrid-css')
  <link rel="stylesheet" href="{{ asset('packages/joesama/vuegrid/css/bootstrap.min.css') }}"/>
@endpush

<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
		<div class="panel panel-default">
		  <div class="panel-body">
		  	@include('joesama/vuegrid::datagrid')
		  </div>
		</div>
    </div>
  </div>
</div>

@push('vuegrid-js')
<script type="text/javascript" src="{{ asset('packages/joesama/vuegrid/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/joesama/vuegrid/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('packages/joesama/vuegrid/js/sweetalert.min.js') }}"></script>
<script src="{{ asset('packages/joesama/vuegrid/js/vue.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>
<script type="text/javascript">
  Vue.config.devtools = true;
  var app = @json($data);
</script>
<script src="{{ asset('packages/joesama/vuegrid/js/datagrid.js') }}"></script>
@endpush
