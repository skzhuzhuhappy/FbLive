<?php

namespace App\Admin\Controllers;

use App\Models\Feeds;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class FeedsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Feeds';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Feeds());

        $grid->column('id', __('Id'));
        $grid->column('from_id', __('From id'));
        $grid->column('group_id', __('Group id'));
        $grid->column('user_id', __('User id'));
        $grid->column('pid', __('Pid'));
        $grid->column('feed_content', __('Feed content'));
        $grid->column('text_body', __('Text body'));
        $grid->column('like_count', __('Like count'));
        $grid->column('feed_view_count', __('Feed view count'));
        $grid->column('feed_comment_count', __('Feed comment count'));
        $grid->column('location', __('Location'));
        $grid->column('longitude', __('Longitude'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('geo_hash', __('Geo hash'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));
        $grid->column('feed_id', __('Feed id'));
        $grid->column('recommended_at', __('Recommended at'));
        $grid->column('feed_client_ip', __('Feed client ip'));
        $grid->column('feed_mark', __('Feed mark'));
        $grid->column('repostable_type', __('Repostable type'));
        $grid->column('repostable_id', __('Repostable id'));
        $grid->column('hot', __('Hot'));
        $grid->column('shares_count', __('Shares count'));
        $grid->column('visible', __('Visible'));
        $grid->column('is_comment', __('Is comment'));

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
        $show = new Show(Feeds::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('from_id', __('From id'));
        $show->field('group_id', __('Group id'));
        $show->field('user_id', __('User id'));
        $show->field('pid', __('Pid'));
        $show->field('feed_content', __('Feed content'));
        $show->field('text_body', __('Text body'));
        $show->field('like_count', __('Like count'));
        $show->field('feed_view_count', __('Feed view count'));
        $show->field('feed_comment_count', __('Feed comment count'));
        $show->field('location', __('Location'));
        $show->field('longitude', __('Longitude'));
        $show->field('latitude', __('Latitude'));
        $show->field('geo_hash', __('Geo hash'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('feed_id', __('Feed id'));
        $show->field('recommended_at', __('Recommended at'));
        $show->field('feed_client_ip', __('Feed client ip'));
        $show->field('feed_mark', __('Feed mark'));
        $show->field('repostable_type', __('Repostable type'));
        $show->field('repostable_id', __('Repostable id'));
        $show->field('hot', __('Hot'));
        $show->field('shares_count', __('Shares count'));
        $show->field('visible', __('Visible'));
        $show->field('is_comment', __('Is comment'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Feeds());

        $form->switch('from_id', __('From id'));
        $form->number('group_id', __('Group id'));
        $form->number('user_id', __('User id'));
        $form->number('pid', __('Pid'));
        $form->textarea('feed_content', __('Feed content'));
        $form->textarea('text_body', __('Text body'));
        $form->number('like_count', __('Like count'));
        $form->number('feed_view_count', __('Feed view count'));
        $form->number('feed_comment_count', __('Feed comment count'));
        $form->text('location', __('Location'));
        $form->text('longitude', __('Longitude'));
        $form->text('latitude', __('Latitude'));
        $form->text('geo_hash', __('Geo hash'));
        $form->switch('status', __('Status'));
        $form->number('feed_id', __('Feed id'));
        $form->number('recommended_at', __('Recommended at'));
        $form->text('feed_client_ip', __('Feed client ip'));
        $form->number('feed_mark', __('Feed mark'));
        $form->text('repostable_type', __('Repostable type'));
        $form->number('repostable_id', __('Repostable id'));
        $form->number('hot', __('Hot'));
        $form->number('shares_count', __('Shares count'));
        $form->number('visible', __('Visible'));
        $form->number('is_comment', __('Is comment'));

        return $form;
    }
}
