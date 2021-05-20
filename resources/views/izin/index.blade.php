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
               

               <form role="form" method="post" action="{{ url('izin') }}">
                {{ csrf_field() }}
                  <div class="box-body">

                    <div class="form-group">
                      <label for="exampleInputEmail1">Karyawan</label>
                      <select class="form-control select2" name="user">
                          @foreach($user as $us)
                          <option value="{{ $us->id }}">{{ $us->name }}</option>
                          @endforeach
                      </select>
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Tanggal</label>
                      <input type="text" autocomplete="off" class="form-control datepicker" name="tanggal" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                      <label for="exampleInputEmail1">Alasan</label>
                      <select class="form-control select2" name="alasan">
                          <option value="S">Sakit</option>
                          <option value="I">Izin</option>
                          <option value="C">Cuti</option>
                          <option value="DL">Dinas Lapangan</option>
                      </select>
                    </div>
                    
                    <!-- <div class="form-group">
                      <label for="exampleInputFile">Surat sakit / sejenis nya *optional</label>
                      <input type="file" name="sample_file" id="exampleInputFile">
     
                      <p class="help-block">Example block-level help text here.</p>
                    </div> -->
                    
                  </div>
                  <!-- /.box-body -->
     
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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