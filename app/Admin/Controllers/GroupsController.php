<?php

namespace App\Admin\Controllers;

use App\Models\GroupCategories;
use App\Models\Groups;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class GroupsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Models\Groups';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Groups());

        $grid->column('id', __('小队id'));
        $grid->column('name', __('小队名称'));
        $grid->column('user_id', __('创建用户id'));
        $grid->column('category_id', __('小队类型id'));
        //$grid->column('area_id', __('小队地区id'));
        //$grid->column('img_head', __('圈子头像'));
        //$grid->column('img_top', __('圈子顶图'));
        $grid->column('img_head','圈子头像')->image('', 100, 100);
        $grid->column('img_top','圈子顶图')->image('', 100, 100);

        //$grid->column('location', __('Location'));
        //$grid->column('longitude', __('Longitude'));
        //$grid->column('latitude', __('Latitude'));
        //$grid->column('geo_hash', __('Geo hash'));
        //$grid->column('allow_feed', __('是否允许发布到动态'))->using(['0' => '是', '1' => '否']);
        $grid->column('summary', __('简介'));
        $grid->column('users_count', __('成员数量'));
        $grid->column('posts_count', __('帖子数量'));
        $grid->column('publish_permission', __('发言权限'))->using(['1' => '全部', '2' => '管理员和组员','3'=>'管理员']);
        //$grid->column('money', __('Money'));
        $grid->column('join_permission', __('加入权限'))->using(['1' => '随意', '2' => '申请','3'=>'付费']);
        $grid->column('feed_status', __('发布动态是否需要审核 '))->using(['0'=>'不需要','1' => '需要']);
        //$grid->column('visible', __('Visible'));
        $grid->column('status', __('审核状态'))->using(['0'=>'待审核','1' => '通过', '2' => '拒绝']);
        //$grid->column('node', __('是否收费'))->using(['1'=>'不收费','2' => '收费']);
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));
        //$grid->column('deleted_at', __('Deleted at'));
        $grid->column('recommend', __('评论数量'));
        //$grid->column('invited_audit', __('被邀请用户是否需要审核'))->using(['0'=>'不需要','1' => '需要']);
        //$grid->column('expense', __('Expense'));
        //$grid->column('divide', __('Divide'));
        $grid->model()->orderBy('created_at', 'desc');
        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            //$filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('name', '小队名称');
            $filter->like('user_id', '创建用户id');
            $filter->equal('publish_permission', '发言权限')->select(['1' => '全部', '2' => '管理员和组员','3'=>'管理员']);
            $filter->equal('join_permission', '加入权限')->select(['1' => '随意', '2' => '申请','3'=>'付费']);
            $filter->equal('feed_status', '发布动态是否需要审核')->select(['0'=>'不需要','1' => '需要']);
            $filter->equal('status', '审核状态')->select(['0'=>'待审核','1' => '通过', '2' => '拒绝']);
            $filter->scope('sex', '男')->where('sex', '1');
            //$filter->scope('sex', '女')->where('sex', '0');

        });
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
        $show = new Show(Groups::findOrFail($id));

        $show->field('id', __('小队id'));
        $show->field('name', __('小队名称'));
        $show->field('user_id', __('创建用户id'));
        $show->field('category_id', __('小队类型id'));
        //$show->field('area_id', __('Area id'));
        $show->field('img_head', __('圈子头像'));
        $show->field('img_top', __('圈子顶图'));
        //$show->field('location', __('Location'));
        //$show->field('longitude', __('Longitude'));
        //$show->field('latitude', __('Latitude'));
        //$show->field('geo_hash', __('Geo hash'));
        //$show->field('allow_feed', __('Allow feed'));
        $show->field('summary', __('简介'));
        $show->field('users_count', __('成员数量'));
        $show->field('posts_count', __('帖子数量'));
        $show->field('publish_permission', __('发言权限'))->using(['1' => '全部', '2' => '管理员和组员','3'=>'管理员']);
        //$show->field('money', __('Money'));
        $show->field('join_permission', __('加入权限'))->using(['1' => '随意', '2' => '申请','3'=>'付费']);
        $show->field('feed_status', __('发布动态是否需要审核'))->using(['0'=>'不需要','1' => '需要']);
        //$show->field('visible', __('Visible'));
        $show->field('status', __('审核状态'))->using(['0'=>'待审核','1' => '通过', '2' => '拒绝']);
        //$show->field('node', __('是否收费'))->using(['1'=>'不收费','2' => '收费']);
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));
        //$show->field('deleted_at', __('Deleted at'));
        $show->field('recommend', __('评论数量'));
        //$show->field('invited_audit', __('Invited audit'));
        //$show->field('expense', __('Expense'));
        //$show->field('divide', __('Divide'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Groups());
        $form->text('name', __('小队名称1'));
        $form->display('user_id', __('创建用户id'));
        //获得类型列表
        $category_list = GroupCategories::categoryList();
        $category = array();
        foreach($category_list as $k=>$v){
            $category[$v['id']] = $v['name'];
        }
        //var_dump(json_encode($category));exit();
        $form->select('category_id', __('类型'))->options($category)->load('children', '/admin/groups/category');
        $form->select('children');

        //$form->number('area_id', __('Area id'));
        $form->image('img_head', __('圈子头像'));
        $form->image('img_top', __('圈子顶图'));
        //$form->image($column[, $label])->crop(int $width, int $height, [int $x, int $y]);

        //$form->text('location', __('Location'));
        //$form->text('longitude', __('Longitude'));
        //$form->text('latitude', __('Latitude'));
        //$form->text('geo_hash', __('Geo hash'));
        //$form->number('allow_feed', __('Allow feed'));
        $form->textarea('summary', __('简介'));
        $publish_permission = [
            '1' => '全部', '2' => '管理员和组员','3'=>'管理员'
        ];
        $form->select('publish_permission', __('发言权限'))->options($publish_permission);
        //$form->number('money', __('Money'));
        $join_permission = [
            '1' => '随意', '2' => '申请','3'=>'付费'
        ];
        $form->select('join_permission', __('加入权限'))->options($join_permission);
        $form->switch('feed_status', __('发布动态是否需要审核'));
        //$form->switch('visible', __('Visible'));
        $status = [
            '0'=>'待审核','1' => '通过', '2' => '拒绝'
        ];
        $form->select('status', __('审核状态'))->options($status);
        //$form->switch('node', __('是否收费'))->default(0);
        $form->number('recommend', __('评论数量'));
        $form->number('users_count', __('成员数量'));
        $form->number('posts_count', __('帖子数量'));
        //$form->number('invited_audit', __('Invited audit'));
        //$form->number('expense', __('Expense'));
        //$form->number('divide', __('Divide'));

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

    public function category(Request $request)
    {
        $provinceId = $request->get('q');
        var_dump($provinceId);exit();
        return  GroupCategories::categoryListParent_id($provinceId);
    }
}
