<?php

namespace Rebelworks\Filemanager\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;

class FileManagerController extends Controller
{
    protected function index(Request $request) {
        $fieldName = $request->get('field_name');
        $type = $request->get('type');
        $dir = $request->get('dir');
        $parent = $request->get('parent');
        $s3 = \Storage::disk(config('filemanager.disk'));
        $prefix = config('filemanager.prefix')."/";
        
        if ('shared' !== $parent) {
            $parent = 'root';
        }
        
        $currentDir = $prefix;
        if ('root' === $parent) {
            $currentDir .= \Auth::user()->id;
        }
        else {
            $currentDir .= 'shared';
        }
        
        $currentDirThumb = $currentDir."_thumb";
        
        if ($dir) {
            $currentDir .= $dir;
            $currentDirThumb .= $dir;
        }
        
        // upload file
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $this->uploadFile($request->file('file'), $s3, $currentDir, $currentDirThumb);
        }
        
        // baca file yg ada di dalam folder user
        $files = $s3->files($currentDir);
        if ($request->ajax()){
            return view('filemanager::imgcontent', compact('s3', 'files'));
        }
        
        //create directory
        if ($request->get('new_directory')) { 
            $s3->makeDirectory($currentDir.'/'.$request->get('new_directory'));
        }
        
        // baca folder shared dalam bentuk tree
        $shared = $this->buildTree($s3->allDirectories($prefix.'shared'));
        
        // baca folder user dalam bentuk tree
        $root = $this->buildTree($s3->allDirectories($prefix.\Auth::user()->id));
        
        return view('filemanager::index', compact('parent', 'dir', 'fieldName', 'type', 's3', 'files', 'shared', 'root'));
    }
    
    private function uploadFile($file, $s3, $currentDir, $currentDirThumb){
        $manager = new ImageManager(['driver' => 'imagick']);
        $img = $manager->make($file->getRealPath());
        
        if (!in_array($img->mime(), config('filemanager.valid_image_mimetypes'))) {
            return false;
        }

        if ($img->width() > 1024) {
            $img->resize(1024, null, function($constraint){
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $s3->put(
            $currentDir.'/'.$file->getClientOriginalName(), 
            $img->stream(null, config('filemanager.quality'))->__toString(), 
            'public'
        );
        
        $s3->put(
            $currentDirThumb.'/'.$file->getClientOriginalName(), 
            $img->fit(150, 150)->stream(null, config('filemanager.quality'))->__toString(), 
            'public'
        );
        
        return true;
    }


    private function buildTree($pathList) {
        $pathTree = array();
        foreach ($pathList as $path) {
            $list = explode('/', trim($path, '/'));
            $lastDir = &$pathTree;
            foreach ($list as $dir) {
                $lastDir =&$lastDir[$dir];
            }
        }
        return $pathTree;
    }
}
