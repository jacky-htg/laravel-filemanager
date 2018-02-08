@php
function buildList($tree, $baseUrl, $dir='', $parent='') {
    $li = '';
        
    foreach ($tree as $key => $value) {
        $curDir = $dir."/".$key;  
        if (is_array($value)) {
            $li .= "<li><a href='{$baseUrl}&dir={$curDir}' data-dir='{$parent}{$curDir}'>{$key}</a>";
            $li .= buildList($value, $baseUrl, $curDir, $parent);
            $li .= "</li>";
        }
        else {
            $li .= "<li><a href='{$baseUrl}&dir={$curDir}' data-dir='{$parent}{$curDir}'>{$key}</a></li>";
        }
    }
    
    return "<ul>{$li}</ul>";    
}

$curUrl = url('/filemanager')."?field_name={$fieldName}&type={$type}";
@endphp

@extends('filemanager::main')

@section('content')
<div id="mainContent">
    <div class="sidebar">
        <h2>Directory</h2>
        <div id="jstree_root">
            <ul>
                <li><a href="{{$curUrl}}">Root</a>
                @php if ($root) echo buildList($root[config('filemanager.prefix')][\Auth::user()->id], $curUrl); @endphp
                </li>
            </ul>
        </div>

        <div id="jstree_shared">
            <ul>
                <li><a href="{{$curUrl}}&parent=shared">Shared</a>
                @php if($shared) echo buildList($shared[config('filemanager.prefix')]['shared'], $curUrl.'&parent=shared'); @endphp
                </li>
            </ul>
        </div>
    </div>
    <div class="imgContent">
        @include('filemanager::imgcontent')
    </div>
</div>
@endsection