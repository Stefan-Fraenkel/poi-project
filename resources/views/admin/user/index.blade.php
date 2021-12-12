@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Benutzerverwaltung</h2>
@stop

@section('content')

<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet">

<div class="col-lg-12 col-sm-6 col-lg-4" style="padding-left: 1%; padding-right: 1%">
    <div class="card card-dark card-tabs">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#users" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">Benutzer</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#permissions" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Berechtigungen</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#roles" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Rollen</a>
                </li>

            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">

                <div class="tab-pane fade active show" id="users" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                            <div id="userpermission_wrapper" class="dataTables_wrapper">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="userpermissions" class="table table-hover dataTable dtr-inline user_management-datatable" role="grid" aria-describedby="userpermissions_info" style="width:100%" >
                                            <thead>
                                            <tr role="row" >
                                                <th class="userpermissionsentry" tabindex="0" aria-controls="userpermissions" rowspan="1" colspan="1" aria-label="Foto: activate to sort column ascending"><center>Foto</center></th>
                                                <th class="userpermissionsentry" tabindex="0" aria-controls="userpermissions" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending"><center>Name</center></th>
                                                <th class="userpermissionsentry" tabindex="0" aria-controls="userpermissions" rowspan="1" colspan="1" aria-label="E-Mail: activate to sort column ascending"><center>E-Mail</center></th>
                                                <th class="userpermissionsentry" tabindex="0" aria-controls="userpermissions" rowspan="1" colspan="1" aria-label="Perimissions: activate to sort column ascending"><center>Berechtigungen</center></th>
                                                <th class="userpermissionsentry" tabindex="0" aria-controls="userpermissions" rowspan="1" colspan="1" aria-label="Roles: activate to sort column ascending"><center>Rollen</center></th>
                                                <th class="userpermissionsentry" tabindex="0" aria-controls="userpermissions" rowspan="1" colspan="1" aria-label="Actions: activate to sort column ascending"><center>Aktionen</center></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <div style="padding-top: 1.5%">
                                    <a href="/admin/user/create" style ="float: right" class="btn btn-success text-uppercase" > anlegen</a>
                        </div>
                </div>

                <div class="tab-pane fade" id="permissions" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                    <div class="col">
                        <div class="card-body">
                            <h2 class="h5 font-weight-bold">
                                Bestehende:
                            </h2>
                            {!! $permissions !!}
                        </div>
                        <div style="padding-left: 1.3%; padding-top: 0%">
                            <a href="/admin/perm/delete" style="float: left" class="btn btn-danger mr-2 mt-2 text-uppercase" ><i class="fas fa-trash"></i>&nbsp; löschen</a>
                            <a href="/admin/perm/create" style="float: right" class="btn btn-success mt-2 text-uppercase" > anlegen</a>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="roles" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                    <div class="col">
                            <div class="card-body">
                                <h2 class="h5 font-weight-bold">
                                    Bestehende:
                                </h2>
                                @foreach ($roles as $role)
                                    {{$role->name}} &nbsp;&nbsp;&nbsp;&nbsp;
                                @endforeach
                            </div>
                                <div style="padding-left: 1.3%; padding-top: 0%">
                                    <a href="/admin/role/delete" style="float: left" class="btn btn-danger mr-2 mt-2 text-uppercase" > löschen</a>
                                    <a href="/admin/role/create" style="float: right" class="btn btn-success mt-2 text-uppercase" > anlegen</a>
                                    <button type="button" style="float: right" class="btn btn-warning mr-2 mt-2 dropdown-toggle dropdown-icon mr-2 text-uppercase" data-toggle="dropdown" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                        bearbeiten
                                    </button>
                                    <div class="dropdown-menu" role="menu" style="position: absolute; transform: translate3d(68px, 38px, 0px); top: 0px; left: 0px; will-change: transform;" x-placement="bottom-start">
                                        @foreach ($roles as $role)
                                            <a class="dropdown-item" href="/admin/role/edit/{{$role->name}}">{{$role->name}}</a>
                                        @endforeach
                                    </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </div>

@stop

@section('js')

<script src="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css"></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">

$(function () {

    var table = $('.user_management-datatable').DataTable({

        processing: true,
        serverSide: true,
        responsive: true,

        "order": [[ 1, 'asc' ]],

        "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/German.json"
            },

        ajax: "{{ route('admin.user.index') }}",

        columns: [
            {data: 'profile_photo_path', name: 'foto', render: function(data,type,row){
              if(data){
                return '<center><img style="vertical-align: middle;" src='+data+'"/storage", alt="'+row.name+'", class="rounded-circle", width=30px, height=30px, /></center>'
              }
              else return '<center><img style="vertical-align: middle;" src="https://ui-avatars.com/api/?name='+row.name+'&color=7F9CF5&background=EBF4FF", class="rounded-circle", width=30px, height=30px, /></center>'
                }, orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'permissions_name[<br>]', name: 'berechtigungen', render: function(data,type,row){
                    if(data){
                        return '<center><a class="btn btn-primary btn-block btn-sm dropdown-toggle text-white text-primary text-uppercase" style="width: 150px" onclick="myFunction('+'1'+row.id+')">anzeigen &nbsp</a><div class="box" style="display:none; padding-top: 10px; padding-bottom: 10px; width: 146px; border-bottom-left-radius: 3px; border-bottom-right-radius: 3px; background; border-style: solid; border-top: none; border-width: 1px; border-color: #007bff;" id='+'1'+row.id+'>'+data+'</div></center>'
                    }
                    else return '<center><a class="btn btn-primary disabled btn-block btn-sm text-white text-primary text-uppercase" style="width: 150px">keine</a></center>'
                }, orderable: false, searchable: false},
            {data: 'roles[<br>].name', name: 'rollen',render: function(data,type,row){
                    if(data){
                        return '<center><a class="btn btn-primary btn-block btn-sm dropdown-toggle text-white text-primary text-uppercase" style="width: 150px" onclick="myFunction('+'2'+row.id+')">anzeigen &nbsp</a><div class="box" style="display:none; padding-top: 10px; padding-bottom: 10px; width: 146px; border-bottom-left-radius: 3px; border-bottom-right-radius: 3px; background; border-style: solid; border-top: none; border-width: 1px; border-color: #007bff;" id='+'2'+row.id+'>'+data+'</div></center>'
                    }
                    else return '<center><a class="btn btn-primary disabled btn-block btn-sm text-white text-primary text-uppercase" style="width: 150px">keine</a></center>'
                }, orderable: false, searchable: false},
            {data: 'id', name: 'action', render: function(data,type,row){
                return '<center><a class="btn btn-warning btn-sm text-uppercase" data-custom="open_modal" data-heading="Benutzer bearbeiten" href="/admin/user/edit/'+data+'" style="width: 150px"><i class="fas fa-pencil-alt"></i>&nbsp; bearbeiten</a></center>'
                }, orderable: false, searchable: false},
        ]
    })

  });

</script>

        <script>

            $("body").on('click', "[data-custom='open_modal']", function (event) {
                event.preventDefault();
                var btn = $(this);
                var link = $(this).attr('href');
                var title = $(this).attr('data-heading');
                $('#custom_modal_resource').remove();
                var modal = '<div class="modal fade" id="custom_modal_resource" aria-modal="true">\
    	<div class="modal-dialog">\
    	<div class="modal-content">\
    	<div class="modal-header">\
    	<h4 class="modal-title">'+title+'</h4>\
    	<button type="button" class="close " data-dismiss="modal" aria-hidden="true">&times;</button>\
    	</div>\
    	<div class="modal-body">\
    	</div>\
    	</div>\
    	</div>';
                $('body').append(modal);
                $('#custom_modal_resource').modal('show');
                $('#custom_modal_resource .modal-body').load(link);
            });

        </script>

<script>
    function myFunction(id) {
        var x = document.getElementById(id); //seems not to be working with string (even though it should), thus paramater given as int (above: +'1'+row.id+)
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function toggleButton(id) {
        var button = document.getElementById(id);
        if (button.className === "fas fa-plus") {
            button.className = "fas fa-minus";
            $('.collapse').collapse()
        } else {
            button.className = "fas fa-plus";
        }

    }

    $(function() {
        // Find list items representing folders and style them accordingly.  Also, turn them into links that can expand/collapse the tree leaf.
        $('li > ul').each(function(i) {
            // Find this list's parent list item.
            var parent_li = $(this).parent('li');
            // Style the list item as folder.
            parent_li.addClass('folder');
            // Temporarily remove the list from the parent list item, wrap the remaining text in an anchor, then reattach it.
            var sub_ul = $(this).remove();
            parent_li.wrapInner('<a/>').find('a').click(function() {
                // Make the anchor toggle the leaf display.
                sub_ul.toggle();
            });
            parent_li.append(sub_ul);
        });
        // Hide all lists except the outermost.
        $('ul ul').hide();
    });

</script>

@stop


