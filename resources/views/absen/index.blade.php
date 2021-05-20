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

                <!-- <h1 class="">Absensi</h1> -->

                <form method="POST" action="#">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3 col-xs-12">
                            <!-- <center> -->
                                <div id="my_camera"></div>
                                <br/>
                                <!-- <input type=button value="Ambil Photo" onClick="take_snapshot()"> -->
                                <input type="hidden" name="image" class="image-tag">
                            <!-- </center> -->
                        </div>
                        <div class="col-md-6">
                            <div id="results" style="display: none;">Your captured image will appear here...</div>
                        </div>
                        <div class="col-md-12 text-center">
                            <br/>
                            <!-- <img src=""> -->
                            <p>
                                <button class="btn btn-success absen-masuk btn-block" onClick="take_snapshot()">Absen Masuk</button>
                                <button class="btn btn-danger absen-pulang btn-block" onClick="take_snapshot()">Absen Pulang</button>
                            </p>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

    <!-- Configure a few settings and attach camera -->
    <script language="JavaScript">
        

        $("input[name='nik']").keypress(function(e){
            if(e.which == 13){
                e.preventDefault();
            }
        })

        $('.absen-masuk').click(function(){
            var url = "{{ url('store/masuk') }}";
            $('form').attr('action',url);
        })

        $('.absen-pulang').click(function(){
            var url = "{{ url('store/pulang') }}";
            $('form').attr('action',url);
        })

        Webcam.set({
            width: 320,
            height: 230,
            image_format: 'jpeg',
            jpeg_quality: 50
        });

        Webcam.attach( '#my_camera' );

        function take_snapshot() {
            Webcam.snap( function(data_uri) {
                $(".image-tag").val(data_uri);
                document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
            } );
            $('.btn-absen').fadeIn();
        }
    </script>

    @endsection