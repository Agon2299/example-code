<?php

namespace App\Http\Controllers;

use App\Entities\Cashbox\Models\Cashbox;
use App\Entities\Category\Models\Category;
use App\Entities\Shop\Models\Shop;
use App\Entities\Subcategory\Models\Subcategory;
use App\Entities\Tag\Models\Tag;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('home', ['user' => Auth::user()]);
    }
}
