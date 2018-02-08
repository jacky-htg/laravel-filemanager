@if(sizeof($files)>0)
@foreach($files as $f)
@php 
    $temp = explode("/", $f);
    $temp[1] .= "_thumb";
    $thumbnail = implode("/", $temp);
    unset($temp);
@endphp
<div class="div-img">
    <div class="box-img">
        <div class="frame-img">
            <img data-src="{{$s3->url($f)}}" src="{{$s3->url($thumbnail)}}" class="img" style="cursor: pointer">
        </div>
        {{basename($f)}}
    </div>
</div>
@endforeach
@else
Folder is empty
@endif
