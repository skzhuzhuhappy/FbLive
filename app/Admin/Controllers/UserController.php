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

        $grid->column('id', __('Id'));
        $grid->column('forum_user_id', __('Forum user id'));
        $grid->column('name', __('Name'));
        $grid->column('password', __('Password'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone'));
        $grid->column('introduct', __('Introduct'));
        $grid->column('sex', __('Sex'));
        $grid->column('location', __('Location'));
        $grid->column('avatar', __('Avatar'));
        $grid->column('bg', __('Bg'));
        $grid->column('last_token', __('Last token'));
        $grid->column('status', __('Status'));
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('phone_verified_at', __('Phone verified at'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

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

        $show->field('id', __('Id'));
        $show->field('forum_user_id', __('Forum user id'));
        $show->field('name', __('Name'));
        $show->field('password', __('Password'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('introduct', __('Introduct'));
        $show->field('sex', __('Sex'));
        $show->field('location', __('Location'));
        $show->field('avatar', __('Avatar'));
        $show->field('bg', __('Bg'));
        $show->field('last_token', __('Last token'));
        $show->field('status', __('Status'));
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('phone_verified_at', __('Phone verified at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

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

        $form->number('forum_user_id', __('Forum user id'));
        $form->text('name', __('Name'));
        $form->password('password', __('Password'));
        $form->email('email', __('Email'));
        $form->mobile('phone', __('Phone'));
        $form->text('introduct', __('Introduct'));
        $form->switch('sex', __('Sex'));
        $form->text('location', __('Location'));
        $form->image('avatar', __('Avatar'));
        $form->text('bg', __('Bg'));
        $form->textarea('last_token', __('Last token'));
        $form->switch('status', __('Status'));
        $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
        $form->datetime('phone_verified_at', __('Phone verified at'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
