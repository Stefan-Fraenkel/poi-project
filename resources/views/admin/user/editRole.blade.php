@extends('adminlte::page')

@section('content_header')
    <h2 class="h4 font-weight-bold">Rolle anpassen</h2>
    <div style="padding-left: 1%; padding-right: 1%">
        <div class="card">
    <form method="POST" action="/admin/role/edit">
        @csrf
        <div class="field"style="padding-left: 1%; padding-top: 1%">
            <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="name">Ausgewählte Rolle:</label>
                <br>
                {{$role}}
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="permissions">Welche Berechtigungen soll die Rolle haben?</label>
            <br>
            {!! $permission_roles_html !!}

        </div>
        <br>
            <input type="hidden" name="role" value="{{$role}}">
        </div>
        <div class="card-footer justify-content-end">
            <div class="align-items-baseline" >
                <div class="control">
                    <button class="submit btn btn-dark text-uppercase" name="update_button" type="submit" style="float: right" >Speichern</button>
                </div>
            </div>
        </div>

@stop

@section('js')

            <script type="text/javascript">

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
