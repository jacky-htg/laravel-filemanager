<header>
    <h1>The Shonet File Manager</h1>
    <br/>
    <form class="form" method="post" style="float:left;">
        <input type="hidden" name="_token" class="_token" value="">
        <input name="new_directory">
        <input name="field_name" type="hidden" value="{{$fieldName}}">
        <input name="type" type="hidden" value="{{$type}}">
        <input type="submit" value="Create New Directory">
    </form>
    <form class="form" method="post" enctype="multipart/form-data" style="float:right">
        <input type="hidden" name="_token" class="_token" value="">
        <input type="file" name="file">
        <input type="submit" value="Upload File">
    </form>

    <div style="clear:both;height:0;">&nbsp;</div>
</header>