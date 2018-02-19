<?php
/**
 * Created by PhpStorm.
 * User: lijunjun
 * Date: 2018/2/16
 * Time: 上午12:21
 */

namespace Api\Logic;

use Api\Service\ClassService;
use Api\Service\ClassServie;
use Api\Service\CourseService;
use Api\Service\WeixinService;

/*
 * 基于课程的类，调用课程类的用户都是已绑定用户
 */

class CourseLogic extends UserBaseLogic {
    /*
     * 创建一个课程
     */
    public function create ()
    {
        $openid = session('openid');
        $weixinService = new WeixinService();
        $weixin_user_result = $weixinService->getByOpenid($openid);
        if ($weixin_user_result['success'] === FALSE) {
            return $weixin_user_result;
        }
        $uid = $weixin_user_result['data']['uid'];
        $user_type = $weixin_user_result['data']['type'];
        if (!$uid || !is_numeric($uid)) {
            return $this->setError('uid参数错误');
        }
        if ($user_type != WeixinService::$USER_TYPE_TEACHER) {
            return $this->setError('不是教师用户');
        }
        // 入参校验
        $course_name = I('course_name');
        $course_img = I('course_img');
        $course_class_name = I('course_class_name');
        $course_remark = I('course_remark');
        if (!$course_name || !$course_img || !$course_class_name) {
            return $this->setError('参入参数不能为空');
        }
        // todo  课程数量要做限制
        $courseService = new CourseService();
        $crease_result = $courseService->create($uid, $course_name, $course_class_name, $course_img, $course_remark);
        if ($crease_result['success'] === FALSE) {
            return $this->setError($crease_result['message']);
        }
        return $this->setSuccess([], '添加成功');
    }

    /*
     * list 正在使用的课程
     */
    public function list_in_use ()
    {
        $uid = session('uid');
        $user_type = session('user_type');
        $page = intval(I('page'));
        $page_size = 20;
        if (!$page) {
            $page = 1;
        }
        // 教师用户从course表查找
        if ($user_type == WeixinService::$USER_TYPE_TEACHER) {
            $courseService = new CourseService();
            $course_items = $courseService->list_in_use_for_teacher($uid, $page, $page_size);
            $course_count = D('Course')->countCourseByUid($uid);

        } elseif ($user_type == WeixinService::$USER_TYPE_STUDENT) {
            $classService = new ClassService();
            $course_items = $classService->list_in_use_for_student($uid, $page, $page_size);
            $course_count = D('Class')->countClassByUid($uid);
        }
        $this->hasMorePage($course_count, $page, $page_size);
        if ($course_items) {
            return $this->setSuccess($course_items, '获取所有课程');
        } else {
            return $this->setError('你还没有创建课程');
        }
    }

    /*
    * list 锁上的所有的课程
    */
    public function list_lock ()
    {

    }

    /*
     * 检索课程
     */
    public function search ()
    {
        $course_id = intval(I('course_id'));
        if (!$course_id) {
            return $this->setError('传入的参数不能为空');
        }
        $courseService = new CourseService();
        $course = $courseService->search($course_id);
        return $course;
    }

    /*
     * 添加课程
     */
    public function add ()
    {
        $course_id = intval(I('course_id'));
        $uid = intval(session('uid'));
        $user_type = intval(session('user_type'));
        if (!$course_id || !$uid || !$user_type) {
            return $this->setError('参数不能为空');
        }
        if ($user_type != WeixinService::$USER_TYPE_STUDENT) {
            return $this->setError('非学生用户不能加入课程');
        }
        $courseService = new CourseService();
        $course_add_result = $courseService->add($uid, $course_id);
        if ($course_add_result['success'] === FALSE) {
            return $this->setError($course_add_result['message']);
        }
        return $this->setSuccess([], '添加成功');
    }

    /*
     * 退出课程
     */
    public function quite ()
    {

    }

    /*
     * 获取当前课程
     */
    public function current ()
    {
        $uid = session('uid');
        $user_type = session('user_type');
        $current_course_id = intval(I('current_course_id'));
        $courseService = new CourseService();
        $course_item_result = $courseService->get_current_course($uid, $user_type, $current_course_id);
        if ($course_item_result['success'] === FALSE) {
            return $this->setError($course_item_result['message']);
        }
        return $this->setSuccess($course_item_result['data']);
    }
}