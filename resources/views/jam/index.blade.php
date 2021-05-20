@extends('layouts.master')
 
@section('content')
 
<div class="row">
    <div class="col-md-12">
        <h4>{{ $title }}</h4>
        <div class="box box-warning">
            <div class="box-header">
                <p>
                    <button class="btn btn-sm btn-flat btn-warning btn-refresh"><i class="fa fa-refresh"></i> Refresh</button>
                </p>
            </div>
            <div class="box-body">
               
               <form role="form" method="post" action="{{ url('jam') }}">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                  <div class="box-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Jam Masuk</label>
                      <input type="text" name="jam_masuk" value="{{ date('H:i',strtotime($dt->jam_masuk)) }}" class="form-control" id="exampleInputEmail1" placeholder="Jam masuk">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputPassword1">Jam Pulang</label>
                      <input type="text" name="jam_pulang" value="{{ date('H:i',strtotime($dt->jam_pulang)) }}" class="form-control" id="exampleInputPassword1" placeholder="Jam pulang">
                    </div>
                    
                  </div>
                  <!-- /.box-body -->
     
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>

            </div>
        </div>
    </div>
</div>
 
@endsection
 
@section('scripts')
 
<script type="text/javascript">
    $(document).ready(function(){
 
        // btn refresh
        $('.btn-refresh').click(function(e){
            e.preventDefault();
            $('.preloader').fadeIn();
            location.reload();
        })
 
    })
</script>
 
@endsection