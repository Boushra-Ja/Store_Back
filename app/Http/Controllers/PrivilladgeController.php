<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Privilladge;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePrivilladgeRequest;

class PrivilladgeController extends BaseController
{

    public function store(Request $request)
    {

        $valid = $request->validate([
            'name' => 'required ',

        ]);

        $privilladge = Privilladge::create([
            'name' => $valid['name'],
        ]);


        if ($privilladge) {
            return $this->sendResponse($privilladge, 'Store Shop successfully');
        } else {
            return $this->sendErrors('failed in Store Shop', ['error' => 'not Store Shop']);

        }

    }

}
