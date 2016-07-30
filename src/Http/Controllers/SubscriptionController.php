<?php

namespace Christhompsontldr\Laraboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

use Christhompsontldr\Laraboard\Models\Subscription;

class SubscriptionController extends Controller
{

    public function show()
    {
        return view('laraboard::subscription.show');
    }
}
