@extends('layouts.master')
 
@section('content')
 
<div class="row">
    <div class="col-md-12">
        <h4>{{ $title }}</h4>
        <div class="box box-warning">
            <div class="box-header">
                <p>
                    <button class="btn btn-sm btn-flat btn-warning btn-refresh"><i class="fa fa-refresh"></i> Refresh</button>

                    <a href="{{ url('karyawan/add') }}" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-plus"></i> Tambah Karyawan</a>
                </p>
            </div>
            <div class="box-body">
               <table class="table table-hover tbl-karyawan">
                   <thead>
                       <tr>
                           <th>#</th>
                           <th>#</th>
                           <th>name</th>
                           <th>email</th>
                           <th>created at</th>
                           <th>updated at</th>
                       </tr>
                   </thead>
               </table>
            </div>
        </div>
    </div>
</div>
 
@endsection
 
@section('scripts')
 
<script type="text/javascript">
    $(document).ready(function(){

        $('.tbl-karyawan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ $yajra }}",
            columns: [
            // or just disable search since it's not really searchable. just add searchable:false
            {data: 'action', name: 'action'},
            {data: 'rownum', name: 'rownum'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},

            ]
        });
 
        // btn refresh
        $('.btn-refresh').click(function(e){
            e.preventDefault();
            $('.preloader').fadeIn();
            location.reload();
        })
 
    })
</script>
 
@endsection