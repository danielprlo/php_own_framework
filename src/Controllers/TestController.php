<?php
namespace DanielPerez\SymfonyRouting\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class testController
{
    public function test(Request $request)
    {
        return new Response(
            '<html><body>THE TEST CONTROLLER WORKS</body></html>'
        );
    }
}