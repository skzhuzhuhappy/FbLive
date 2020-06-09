<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('用户id'));
        $grid->column('forum_user_id', __('论坛id'));
        $grid->column('name', __('用户名'));
        $grid->column('password', __('密码'));
        $grid->column('email', __('邮箱'));
        $grid->column('phone', __('手机号'));
        //$grid->column('introduct', __('Introduct'));
        $grid->column('sex', __('性别'))->using(['0' => '女', '1' => '男']);
        //$grid->column('location', __('Location'));
        $grid->column('avatar', __('头像'));
        //$grid->column('bg', __('Bg'));
        //$grid->column('last_token', __('Last token'));
        $grid->column('status', __('状态'))->using(['0' => '正常', '1' => '禁用']);
        //$grid->column('email_verified_at', __('Email verified at'));
        //$grid->column('phone_verified_at', __('Phone verified at'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));
        //$grid->column('deleted_at', __('Deleted at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('用户id'));
        $show->field('forum_user_id', __('论坛用户id'));
        $show->field('name', __('用户名'));
        $show->field('password', __('密码'));
        $show->field('email', __('邮箱'));
        $show->field('phone', __('手机号'));
        $show->field('introduct', __('用户描述'));
        $show->field('sex', __('性别'));
       // $show->field('location', __('Location'));
        $show->field('avatar', __('头像'));
        //$show->field('bg', __('Bg'));
        //$show->field('last_token', __('Last token'));
        $show->field('status', __('状态'))->using(['0' => '正常', '1' => '禁用']);
        //$show->field('email_verified_at', __('Email verified at'));
        //$show->field('phone_verified_at', __('Phone verified at'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));
        //$show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->number('forum_user_id', __('论坛用户id'));
        $form->text('name', __('用户名'));
        //$form->password('password', __('Password'));
        $form->email('email', __('邮箱'));
        $form->mobile('phone', __('手机号'));
        $form->text('introduct', __('用户描述'));
        $sex = [
            0 => '女',
            1 => '男',
        ];
        $form->select('sex', __('性别'))->options($sex);
        //$form->text('location', __('Location'));
        $form->image('avatar', __('头像'));
        //$form->text('bg', __('Bg'));
        //form->textarea('last_token', __('Last token'));
        $status = [
            0 => '正常',
            1 => '禁用',
        ];
        $form->select('status', __('状态'))->options($status);
        //$form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        //$form->datetime('phone_verified_at', __('Phone verified at'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
