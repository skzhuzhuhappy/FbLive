<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Api')->prefix('v1')->group(function () {
    Route::middleware('api.guard')->group(function () {
        //用户注册
        Route::post('/users', 'UserController@store')->name('users.store');
        //用户登录
        Route::post('/login', 'UserController@login')->name('users.login');
        //测试跨域问题
        Route::get('foo', function () {
            return 'Hello World';
        });
        //圈子详情
        Route::get('/groups/{id}', 'GroupsController@show')->name('groups.show');

        Route::middleware('api.refresh')->group(function () {
            //当前用户信息
            Route::get('/users/info', 'UserController@info')->name('users.info');
            //用户列表
            Route::get('/users', 'UserController@index')->name('users.index');
            //用户信息
            Route::get('/users/{user}', 'UserController@show')->name('users.show');
            //用户退出
            Route::get('/logout', 'UserController@logout')->name('users.logout');

        });

        /*
         * 圈子相关路由
         * */


        //圈子列表
        Route::get('/groups', 'GroupsController@index')->name('groups.index');
        //类型 地点 下的圈子
        Route::get('/catearea/groups', 'GroupsController@cateareaIndex')->name('catearea.groups.index');
        //圈子的类型列表
        Route::get('/categorys/groups', 'GroupsController@categorysIndex')->name('groups.categorys');
        //圈子的地区列表
        Route::get('/areas/groups', 'GroupsController@areasIndex')->name('groups.areas');
        //圈子下的用户列表
        Route::get('/groups/user/{id}', 'GroupsController@groupuserIndex')->name('groups.user.index');
        //某个用户下的圈子列表
        Route::get('/user/groups/{id}', 'GroupsController@useridIndex')->name('userId.groups.index');


        //圈子的动态列表
        Route::get('/feeds/group/{id}', 'FeedsController@groupIndex')->name('feeds.group.index');
        //所有圈子的所有动态
        Route::get('/feeds', 'FeedsController@Index')->name('feeds.index');

        //用户的动态列表
        Route::get('/feeds/user/{id}', 'FeedsController@userIndex')->name('feeds.user.index');
        //动态详情
        Route::get('/feeds/{id}', 'FeedsController@show')->name('feeds.show');

        Route::middleware('api.refresh')->group(function () {

            //登陆用户加入的圈子列表
            Route::get('/user/groups', 'GroupsController@userIndex')->name('user.groups.index');
            //新建圈子
            Route::post('/groups', 'GroupsController@store')->name('groups.store');
            //更新圈子
            Route::put('/groups/{id}', 'GroupsController@update')->name('groups.update');
            //删除
            Route::delete('/groups/{id}', 'GroupsController@destroy')->name('groups.destroy');
            //用户加入圈子
            Route::post('/groupmembers', 'GroupMembersController@store')->name('groupmembers.index');
            //用户退出圈子
            Route::delete('/group/exit/{id}', 'GroupsController@groupExit')->name('group.exit');

            //发布动态
            Route::post('/feeds', 'FeedsController@store')->name('feeds.store');

            //添加动态评论
            Route::post('/feeds/reply', 'FeedsController@reply')->name('feeds.reply');
            //动态点赞
            Route::post('/feedlike', 'FeedLikeController@store')->name('feeds.store');


        });


    });

    Route::middleware('admin.guard')->group(function () {
        //管理员注册
        Route::post('/admins', 'AdminController@store')->name('admins.store');
        //管理员登录
        Route::post('/admin/login', 'AdminController@login')->name('admins.login');
        Route::middleware('api.refresh')->group(function () {
            //当前管理员信息
            Route::get('/admins/info', 'AdminController@info')->name('admins.info');
            //管理员列表
            Route::get('/admins', 'AdminController@index')->name('admins.index');
            //管理员信息
            Route::get('/admins/{user}', 'AdminController@show')->name('admins.show');
            //管理员退出
            Route::get('/admins/logout', 'AdminController@logout')->name('admins.logout');
        });
    });





});