@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Änderungen an Nutzern</h2>
@stop

@section('content')

<section class="content">
    <div class="container-fluid">

        <!-- Timelime example  -->
        <div class="row">
            <div class="col-md-12">
                <!-- The time line -->
                <div class="timeline">
                    <!-- timeline time label -->
                    <div class="time-label">
                        <span class="bg-red">{{$date_now}}</span>
                    </div>
                    <!-- /.timeline-label -->
                   <!-- timeline item -->
                    @foreach ($notifications as $notification)
                    <div>
                        <i class="fas fa-user bg-green"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {{$notification['time']}}</span>
                            @if($notification['data']['type'] == "user_created")
                            <h3 class="timeline-header"><a href="#">{{$notification['cause']}}</a> hat einen Nutzer angelegt</h3>
                            @endif
                            <div class="timeline-body">
                                <div style="text-align: right;">
                                    <img style="float: right;", width=70px, height=70px, src={{$notification['photo']}}>
                                </div>
                                {!!$notification['data']['body']!!}
                            </div>
                        </div>
                    </div>
                @endforeach
                    <!-- END timeline item -->
                    <!-- timeline time label -->
                    <div class="time-label">
                        <span class="bg-green">{{$date_first}}</span>
                    </div>
                    <!-- /.timeline-label -->

                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!-- /.timeline -->

</section>

@stop
