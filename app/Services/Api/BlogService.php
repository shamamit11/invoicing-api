<?php
namespace App\Services\Api;

use App\Models\Blog;

class BlogService
{
    public function list()
    {
        $blogs = Blog::latest()->paginate(10);
        return [
            "status" => 200,
            "data" => $blogs
        ];
    }

}