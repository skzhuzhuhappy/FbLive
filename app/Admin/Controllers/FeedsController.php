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
    protected $title = '动态';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Feeds());

        $grid->column('id', __('动态id'));
        $grid->column('from_id', __('动态来源'))->using(['1' => 'pc', '2' => 'h5','3'=>'ios','4'=>'android','5'=>'其他']);
        $grid->column('group_id', __('圈子id'));
        $grid->column('user_id', __('用户id'));
        //$grid->column('pid', __('Pid'));
        $grid->column('feed_content')->display(function ($pictures) {
            return explode(',',$pictures);
        })->image('', 100, 100);
        $grid->column('text_body', __('动态内容'));
        $grid->column('like_count', __('点赞数'));
        //$grid->column('feed_view_count', __('阅读数'));
        $grid->column('feed_comment_count', __('评论数'));
        //$grid->column('location', __('Location'));
        //$grid->column('longitude', __('Longitude'));
        //$grid->column('latitude', __('Latitude'));
        //$grid->column('geo_hash', __('Geo hash'));
        $grid->column('status', __('审核状态,'))->using(['0'=>'待审核','1' => '通过', '2' => '拒绝']);
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));
        //$grid->column('deleted_at', __('Deleted at'));
        //$grid->column('feed_id', __('Feed id'));
        $grid->column('recommended_at', __('是否为精华'))->using(['0'=>'不是','1' => '是']);
        //$grid->column('feed_client_ip', __('Feed client ip'));
        //$grid->column('feed_mark', __('Feed mark'));
        //$grid->column('repostable_type', __('Repostable type'));
        //$grid->column('repostable_id', __('Repostable id'));
        $grid->column('hot', __('置顶'))->using(['0'=>'不是','1' => '是']);
        //$grid->column('shares_count', __('Shares count'));
        //$grid->column('visible', __('Visible'));
        $grid->column('is_comment', __('是否可以评论'))->using(['0'=>'可以','1' => '不可以']);

        $grid->model()->orderBy('created_at', 'desc');
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '小队名称');
            $filter->like('user_id', '用户id');
            $filter->like('text_body', '动态内容');
            $filter->equal('status', '审核状态')->select(['0'=>'待审核','1' => '通过', '2' => '拒绝']);
            $filter->equal('recommended_at', '是否为精华')->select(['0'=>'不是','1' => '是']);
            $filter->equal('hot', '置顶')->select(['0'=>'不是','1' => '是']);
            $filter->equal('is_comment', '是否可以评论')->select(['0'=>'可以','1' => '不可以']);
            //$filter->scope('sex', '女')->where('sex', '0');

        });
        $grid->disableCreateButton();

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

        $show->field('id', __('动态id'));
        $show->field('from_id', __('动态来源'))->using(['1' => 'pc', '2' => 'h5','3'=>'ios','4'=>'android','5'=>'其他']);
        $show->field('group_id', __('圈子id'));
        $show->field('user_id', __('用户id'));
        //$show->field('pid', __('Pid'));
        //$show->field('feed_content', __('图片'));
        $show->field('feed_content', __('动态图片'))->display(function ($pictures) {
            return explode(',',$pictures);
        })->image('', 100, 100);
        $show->field('text_body', __('动态内容'));
        $show->field('like_count', __('点赞数'));
        //$show->field('feed_view_count', __('Feed view count'));
        $show->field('feed_comment_count', __('评论数'));
        //$show->field('location', __('Location'));
        //$show->field('longitude', __('Longitude'));
        //$show->field('latitude', __('Latitude'));
        //$show->field('geo_hash', __('Geo hash'));
        $show->field('status', __('审核状态'))->using(['0'=>'待审核','1' => '通过', '2' => '拒绝']);
        $show->field('created_at', __('创建时间'))->sortable();
        $show->field('updated_at', __('更新时间'));
        //$show->field('deleted_at', __('Deleted at'));
        //$show->field('feed_id', __('Feed id'));
        $show->field('recommended_at', __('是否为精华'))->using(['0'=>'否','1' => '是']);
        //$show->field('feed_client_ip', __('Feed client ip'));
        //$show->field('feed_mark', __('Feed mark'));
        //$show->field('repostable_type', __('Repostable type'));
        //$show->field('repostable_id', __('Repostable id'));
        $show->field('hot', __('置顶'))->using(['0'=>'否','1' => '是']);
        //$show->field('shares_count', __('Shares count'));
        //$show->field('visible', __('Visible'));
        $show->field('is_comment', __('是否可以评论'))->using(['0'=>'是','1' => '否']);

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
        $from_id = [
            '1' => 'pc', '2' => 'h5','3'=>'ios','4'=>'android','5'=>'其他'
        ];
        $form->select('from_id', __('动态来源'))->options($from_id);
        $form->display('group_id', __('小队id'));
        $form->display('user_id', __('用户id'));
        //$form->number('pid', __('Pid'));
        //$form->multipleImage('feed_content','图片');

        //$form->textarea('feed_content', __('动态图片'));
        $form->textarea('text_body', __('动态内容'));
        $form->number('like_count', __('点赞数'));
        //$form->number('feed_view_count', __('Feed view count'));
        $form->number('feed_comment_count', __('评论数'));
        //$form->text('location', __('Location'));
        //$form->text('longitude', __('Longitude'));
        //$form->text('latitude', __('Latitude'));
        //$form->text('geo_hash', __('Geo hash'));
        $status = [
            '0'=>'待审核','1' => '通过', '2' => '拒绝'
        ];
        $form->select('status', __('审核状态'))->options($status);
        //$form->number('feed_id', __('Feed id'));
        $recommended_at = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('recommended_at', __('是否为精华'))->states($recommended_at);
        //$form->text('feed_client_ip', __('Feed client ip'));
        //$form->number('feed_mark', __('Feed mark'));
        //$form->text('repostable_type', __('Repostable type'));
        //$form->number('repostable_id', __('Repostable id'));
        $hot = [
            'on'  => ['value' => 1, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 0, 'text' => '否', 'color' => 'danger'],
        ];
        $form->switch('hot', __('置顶'))->states($hot);
        //$form->number('shares_count', __('Shares count'));
        //$form->number('visible', __('Visible'));
        $is_comment = [
            'on'  => ['value' => 0, 'text' => '是', 'color' => 'success'],
            'off' => ['value' => 1, 'text' => '否', 'color' => 'danger'],
        ];
        //$form->field('is_comment')->switch($is_comment);

        $form->switch('is_comment', __('是否可以评论'))->states($is_comment);
        // 两个时间显示
        $form->display('created_at', '创建时间');
        $form->display('updated_at', '修改时间');

        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();

            // 去掉`提交`按钮
            //$footer->disableSubmit();

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });

        return $form;
    }
}
