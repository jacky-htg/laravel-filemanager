<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" name="viewport">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>File Manager | Rebelworks</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <style>
        * {
            margin:0;
            padding:0;
        }
        
        html, body, #wrapper, #mainContent {
            width:100%;
        }
        
        ul {
            list-style: none;
        }
        
        header{
            background: #ccc;
            padding: 2%;
            width: 96%;
        }
        
        footer{
            background: #ccc;
            padding: 1%;
            width: 98%;
        }
        
        #mainContent {
            display: table;
        }
        
        .sidebar {
            padding: 2%;
            width : 24%;
            display: table-cell;
            vertical-align: top;
            background: #eee;
        }
        
        .imgContent {
            padding: 2%;
            width : 68%;
            display: table-cell;
            vertical-align: top;
        }
        
        ul {
            padding-left:10%;
        }
        
        .div-img{
            width:160px;
            height:160px;
            display:inline-table;
            vertical-align: top;
            text-align: center;
        }
        .div-img .box-img {
            padding:5px;
            width:150px;
        }
        
        .div-img .box-img .frame-img{
            background: #ccc;
            border: 1px solid #777;
            height:150px;
            width:150px;
        }
        
        img.img{
            width:100%;
        }
        
    </style>
</head>
<body>
    <div class="wide-wrap" id="wrapper">
        @include('filemanager::header')
        @yield('content')
        @include('filemanager::footer')
    </div>

    @section('modal') @show
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
	integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script>
        $(function () { 
            $('#jstree_root').jstree();
            $('#jstree_shared').jstree();
        });
        
        $('#jstree_root').on("changed.jstree", function (e, data) {
            $('#jstree_shared').find('.jstree-clicked').removeClass('jstree-clicked');
            changeTree(data.selected[0]);
        });
        
        $('#jstree_shared').on("changed.jstree", function (e, data) {
            $('#jstree_root').find('.jstree-clicked').removeClass('jstree-clicked');
            changeTree(data.selected[0]);            
        });
        
        var changeTree = function(id){
            fetchData(id);
            var _a = $('#'+id+'_anchor');
            $('.form').each(function(){
                $(this).attr('action', _a.attr('href'));
            });
        };
        
        var fetchData = function(id){
            $.ajax({
                type : 'GET',
                url : $('#'+id+'_anchor').attr('href'),
                success : function (data) {
                    $('.imgContent').html(data);
                },
                error : function (err) {
                    console.log(err);
                }
            });
        };
        
        $('.img').on('click', function(){
            var mainId = '{{$fieldName}}';
            mainId = mainId.split("-inp");
            mainId = mainId[0].split("_");
            descId = mainId[0]+"_"+(parseInt(mainId[1])+1);
            styleId = mainId[0]+"_"+(parseInt(mainId[1])+8);
            
            //console.log($(this).attr('src'));
            window.opener.document.getElementById('{{$fieldName}}').value=$(this).data('src');
            window.opener.document.getElementById(descId).value='Jawaban Kamu apa?';
            window.opener.document.getElementById(styleId).value='width:100%;height:auto;';
            window.close();
            window.opener.focus();
        });
        
        $(document).ready(function(){
            $('._token').val($('meta[name=csrf-token]').attr("content"));
            $('#jstree_root').jstree('open_all');
            $('#jstree_shared').jstree('open_all');
            @if('shared' === $parent)
                @if($dir)
                    $('#jstree_shared').find("[data-dir='shared{{$dir}}']").addClass('jstree-clicked');
                @else
                    $('#jstree_shared').find("a").first().addClass('jstree-clicked');
                @endif
            @else
                @if($dir)
                    $('#jstree_root').find("[data-dir='{{$dir}}']").addClass('jstree-clicked');
                @else
                    $('#jstree_root').find("a").first().addClass('jstree-clicked');
                @endif
            @endif
        });
    </script>
    @section('footer_scripts') @show
</body>
</html>