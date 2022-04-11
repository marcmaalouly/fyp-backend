<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Menus\GetSidebarMenu;
use Illuminate\Http\Response;

class MenuController extends Controller
{
    /**
     * Swagger
     *
     * @OA\Get (
     *      path="/menu",
     *      operationId="sideMenu",
     *      tags={"Menu"},
     *      summary="MenuController@index",
     *      description="Get Side Bar Menu",
     *      security={{"bearerAuth":{}}},
     *       @OA\Response(
     *          response=200,
     *          description="Successful",
     *          @OA\JsonContent(),
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(),
     *      ),
     *  )
     */
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $roles = $user->workspaceRoles()->first();
//        if ($roles->name == 'User')
//        {
//            $roles = $user->projectRoles();
//        }

        if ($request->has('menu')) {
            $menuName = $request->input('menu');
        } else {
            $menuName = 'sidebar menu';
        }

        $menus = new GetSidebarMenu();
        return response()->json($menus->getMenuFromDB($menuName, $roles->id));
    }
}
