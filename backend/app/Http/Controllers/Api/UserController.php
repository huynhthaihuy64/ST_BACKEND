<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ListRequest;
use App\Repositories\PostRepo;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var PostRepo
     */
    private $postRepo;

    public function __construct(UserService $userService, PostRepo $postRepo)
    {
        $this->userService = $userService;
        $this->postRepo = $postRepo;
    }

    /**
     * @param ListRequest $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(ListRequest $request)
    {
        return $this->userService->findAll($request->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->userService->findOne($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
