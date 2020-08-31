<?php

namespace App\Exceptions\Basket;

use Exception;

class DeletedBasketException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        dd('invalid');
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response();
    }
}