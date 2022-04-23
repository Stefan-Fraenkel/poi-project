<form method="POST" action="/admin/user/edit/{{$user->id}}">
    @csrf
    <div class="field"style="padding-left: 1%; padding-top: 1%">
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" value="{{$user->name}}" required>
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="email">E-Mail</label>
            <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}" required>
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="password">Passwort</label>
            <input class="form-control" type="text" name="password" id="password">
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="roles">Rollen</label>
            <br>

            @foreach ($roles as $role)
                <input type="hidden" name="i" value="{{$i=0}}">
                @foreach ($role_users as $role_user)
                    @if($role->name == $role_user)
                        <input type="checkbox" name="roles[]" value="{{$role->name}}" checked> {{$role->name}} &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="hidden" name="i" value="{{$i++}}">
                    @endif
                @endforeach
                @if (!$i>0)
                    <input type="checkbox" name="roles[]" value="{{$role->name}}"> {{$role->name}} &nbsp;&nbsp;&nbsp;&nbsp;
                @endif
            @endforeach
        </div>
        <br>
        <div class="w-md-75" style="padding-left: 1%; padding-right: 2%">
            <label class="label" for="permissions">Berechtigungen</label>
            <br>
            {!! $permissions_html !!}
        </div>
        <br>
        <input type="hidden" name="id" value="{{$user->id}}">
    </div>
    <div style="padding-left: 1%; padding-right: 1%">
        <div class="align-items-baseline" >
            <div class="control">
                <button class="submit btn btn-danger text-uppercase" name="delete_button" type="submit" style="float: left" >Nutzer löschen</button>
                <button class="submit btn btn-dark text-uppercase" name="update_button" type="submit" style="float: right" >Speichern</button>
            </div>
        </div>
    </div>
</form>

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
