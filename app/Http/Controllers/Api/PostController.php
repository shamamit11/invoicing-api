<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\Api\BlogService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $blog;

    public function __construct(BlogService $blogService) {
        $this->blog = $blogService;
    }

    public function index()
    {
       return $this->blog->list();
    }

    public function create()
    {
        //
    }

    public function store()
    {
        //
    }
    public function show(Blog $blog)
    {
        return [
            "status" => 1,
            "data" =>$blog
        ];
    }

    public function edit(Blog $blog)
    {
        //
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
 
        $blog->update($request->all());
 
        return [
            "status" => 1,
            "data" => $blog,
            "msg" => "Blog updated successfully"
        ];
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return [
            "status" => 1,
            "data" => $blog,
            "msg" => "Blog deleted successfully"
        ];
    }

    
}
