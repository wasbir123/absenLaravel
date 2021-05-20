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
               
               <form role="form" method="post" action="{{ url('atasan') }}">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="box-body">

                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Daerah</label>
                    <input type="text" name="daerah" class="form-control" id="exampleInputEmail1" placeholder="Nama Daerah" value="{{ $dt->daerah }}">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Atasan</label>
                    <input type="text" name="atasan" class="form-control" id="exampleInputEmail1" placeholder="Nama Atasan" value="{{ $dt->atasan }}">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Nama Kantor</label>
                    <input type="text" name="kantor" class="form-control" id="exampleInputEmail1" placeholder="Nama Kantor" value="{{ $dt->kantor }}">
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