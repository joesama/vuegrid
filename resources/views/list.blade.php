@push('vuegrid-css')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style type="text/css">
  #griddata caption {
    caption-side: top;
    font-size: 20;
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
<script type="text/javascript" src="{{ asset('packages/joesama/vuegrid/js/jquery.min.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
