@extends('layouts.master')
 
@section('content')
 
<div class="row">
    <div class="col-md-12">
        <h4>{{ $title }}</h4>
        <div class="box box-warning">
            <div class="box-header">
                <p>
                    <button class="btn btn-sm btn-flat btn-warning btn-refresh"><i class="fa fa-refresh"></i> Refresh</button>

                    <button class="btn btn-sm btn-flat btn-primary btn-filter"><i class="fa fa-filter"></i> Filter tanggal</button>

                    <button class="btn btn-sm btn-flat btn-success btn-excel"><i class="fa fa-download"></i> Export</button>
                </p>
            </div>
            <div class="box-body">
               <div class="table-responsive">
                   <table class="table table-hover tbl-absen">
                       <thead>
                           <tr>
                               <th>#</th>
                               <th>#</th>
                               <th>user</th>
                               <th>tanggal</th>
                           </tr>
                       </thead>
                   </table>
               </div>
            </div>
        </div>
    </div>
</div>

<!-- modal filter -->
<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
      <div class="modal-dialog modal-default modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger">
 
          <div class="modal-header">
            <h6 class="modal-title" id="modal-title-notification">Your attention is required</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
 
          <div class="modal-body">
 
            <form role="form" method="get" action="{{ url('absen/history/periode') }}">
              <div class="box-body">
                <div class="form-group">
                <label for="exampleInputEmail1">tgl awal</label>
                  <input type="text" autocomplete="off" name="awal" class="form-control datepicker" id="exampleInputEmail1" placeholder="tgl awal" value="{{ $awal }}">
              </div>
              <div class="form-group">
                  <label for="exampleInputPassword1">tgl akhir</label>
                  <input type="text" autocomplete="off" name="akhir" class="form-control datepicker" id="exampleInputPassword1" placeholder="tgl akhir" value="{{ $akhir }}">
              </div>
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

<!-- modal excel -->
<div class="modal fade" id="modal-excel" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-default modal-dialog-centered modal-" role="document">
      <div class="modal-content bg-gradient-danger">

        <div class="modal-header">
          <h6 class="modal-title" id="modal-title-notification">Your attention is required</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>

        <div class="modal-body">

          <form role="form" method="post" action="{{ url('absen/excel') }}">
            {{ csrf_field() }}
            <div class="box-body">
              <div class="form-group">
                <label for="exampleInputEmail1">tgl awal</label>
                  <input type="text" autocomplete="off" name="awal" class="form-control datepicker" id="exampleInputEmail1" placeholder="tgl awal" value="{{ $awal }}">
              </div>
              <div class="form-group">
                  <label for="exampleInputPassword1">tgl akhir</label>
                  <input type="text" autocomplete="off" name="akhir" class="form-control datepicker" id="exampleInputPassword1" placeholder="tgl akhir" value="{{ $akhir }}">
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Export Excel</button>

              <button type="submit" class="btn btn-success btn-pdf">Export Pdf</button>
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>
 
@endsection
 
@section('scripts')

<script id="details-template" type="text/x-handlebars-template">
        <div class="label label-info">User @{{ id }}'s Posts</div>
        <table class="table details-table" id="posts-@{{id}}">
            <thead>
            <tr>
                <th>absen masuk</th>
                <th>photo masuk</th>
                <th>absen pulang</th>
                <th>photo pulang</th>
                <th>total</th>
            </tr>
            </thead>
        </table>
    </script>
 
<script type="text/javascript">
    $(document).ready(function(){

        $('.btn-pdf').click(function(){
          var url = "{{ url('absen/pdf') }}";

          $('form').attr('action',url);
        })

        $('.btn-excel').click(function(e){
          e.preventDefault();
          $('#modal-excel').modal();
        })

        $('.btn-filter').click(function(){
            $('#modal-filter').modal();
        })

        var template = Handlebars.compile($("#details-template").html());

        var table = $('.tbl-absen').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ $yajra }}",
            columns: [
            // or just disable search since it's not really searchable. just add searchable:false
            {
                "className":      'details-control',
                "orderable":      false,
                "searchable":     false,
                "data":           null,
                "defaultContent": '<button class="btn btn-xs btn-flat btn-success"><i class="fa fa-plus"></i></button>'
            },
            {data: 'rownum', name: 'rownum'},
            {data: 'users.name', name: 'users.name'},
            {data: 'tanggal', name: 'tanggal'},

            ]
        });

        // Add event listener for opening and closing details
        $('.tbl-absen tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var tableId = 'posts-' + row.data().id;

            if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(template(row.data())).show();
            initTable(tableId, row.data());
            tr.addClass('shown');
            tr.next().find('td').addClass('no-padding bg-gray');
        }
    });

        function initTable(tableId, data) {
            $('#' + tableId).DataTable({
                processing: true,
                serverSide: true,
                ajax: data.details_url,
                columns: [
                { data: 'masuks.created_at', name: 'masuks.created_at' },
                { data: 'photo_masuk', name: 'photo_masuk' },
                { data: 'pulangs.created_at', name: 'pulangs.created_at' },
                { data: 'photo_pulang', name: 'photo_pulang' },
                { data: 'total', name: 'total' },
                ]
            })
        }
 
        // btn refresh
        $('.btn-refresh').click(function(e){
            e.preventDefault();
            $('.preloader').fadeIn();
            location.reload();
        })
 
    })
</script>
 
@endsection