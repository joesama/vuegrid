@push('vuegrid-css')
<link rel="stylesheet" href="{{ asset('packages/joesama/vuegrid/css/vuegrid.css') }}" >

<style type="text/css">
  #griddata caption {
    caption-side: top;
    font-size: 20px;
    font-weight: 600;
  }
</style>

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

<script src="{{ asset('packages/joesama/vuegrid/js/vuegrid.js') }}"></script>
<script type="text/javascript" src="https://unpkg.com/sweetalert2@7.19.1/dist/sweetalert2.all.js"></script>
<script src="{{ asset('packages/joesama/vuegrid/js/vue.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>

@if(config('vuegrid.env') != 'production')
<script type="text/javascript">
	Vue.config.devtools = "{{ config('vuegrid.debug',false) }}";
</script>
@endif

<script type="text/javascript">
	var app = @json($data);
</script>
<script src="{{ asset('packages/joesama/vuegrid/js/datagrid.js') }}"></script>
@endpush
