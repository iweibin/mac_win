<?php 
// +----------------------------------------------------------------------
// | 仿网易一元夺宝购物系统
// +----------------------------------------------------------------------
// | Author: 王茂林 (1290800466@qq.com)
// +----------------------------------------------------------------------
// | Tel: 18612446985
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
class AdminController extends Controller {
    //登陆页面
    public function login(){				
		//验证是否已登录
		//管理员的SESSION
		$adm_session = session('admin_info.name');
		if($adm_session != '')
		{
			//已登录
			$this->redirect(u("Index/index"));			
		}
		else
		{
		    
			$this->display();
		}
	}
	
    //生成验证码
	public function verify(){
	    $Verify = new \Think\Verify();
	    $Verify->fontSize = 12;
	    $Verify->length   = 4;
	    $Verify->useNoise = false;
	    $Verify->useCurve = false;
	    $Verify->codeSet = '0123456789';
	    $Verify->entry('verify_code');
	}
	
    //登录函数
    public function do_login()
    
    {		
        $adm_session = session('admin_info.name');
        if($adm_session != '')
        {
            //已登录
            $this->redirect(u("Index/index"));
        }
    	$adm_name = trim($_REQUEST['adm_name']);
    	$adm_password = trim($_REQUEST['adm_password']);
    	if($adm_name == '')
    	{
    	    $this->error('用户名不能为空');
    	}
    	if($adm_password == '')
    	{
    	    $this->error('密码不能为空');
    	}
    	$Verify = new \Think\Verify();
    	$adm_verify=$Verify->authcode1($_REQUEST['adm_verify']);
    	if(session('verify_code.verify_code') != $adm_verify) {
    	    $this->error('验证码错误');
    	}
		$condition['adm_name'] = $adm_name;
		$adm_data = M("Admin")->where($condition)->find();
		if($adm_data) //有用户名的用户
		{
			//if($adm_data['adm_password']!=md5($adm_password))
			if($adm_data['adm_password']!=$adm_password)
			{
				$this->error('账号或密码错误，请重新输入');
			}
			else
			{
				//登录成功
			    session('admin_info.name',$adm_data['adm_name']);  //设置session
			    session('admin_info.id',$adm_data['id']);  //设置session
				$this->success('登陆成功，正在跳转！',SITE_URL.'admin.php?m=Home&c=Index&a=index');
			}
		}
		else
		{
			$this->error('账号或密码错误，请重新输入');
		}
    }

    
    //登出函数
    public function do_loginout()
    {
        //验证是否已登录
        //管理员的SESSION
        $adm_session = session('admin_info');

    
        if($adm_session == '')
        {
            //尚未登录
            session(null); // 清空当前的session
            $this->error('注销成功,正在跳转中。。。','http://www.1qjx.com/admin.php?m=Home&c=Admin&a=login');
        }
        else
        {
            session(null); // 清空当前的session
            $this->success('注销成功,正在跳转中。。。','http://www.1qjx.com/admin.php?m=Home&c=Admin&a=login');
        }
    }
    
    //修改账号密码输入框页面
    public function admin()
    {
        //验证是否已登录
        //管理员的SESSION
        $adm_session = session('admin_info');
	    if($adm_session == '')
	    {
	        //尚未登录
	        $this->error('您尚未登陆，正在跳转到登陆页面。。。',__APP__);
	    }
	    
	    //var_dump($adm_session['id']);
	    $admin['id'] = $adm_session['id'];
	    $admin['name'] = $adm_session['name'];
	    $this->assign("admin",$admin);
	    
	    $this->display(); 
    }
    
    //修改账号密码（插入数据库）
    public function update_admin()
    {
        //验证是否已登录
        //管理员的SESSION
        $adm_session = session('admin_info');
        if($adm_session == '')
        {
            //尚未登录
            $this->error('您尚未登陆，正在跳转到登陆页面。。。',__APP__);
        }
        echo '暂时不能用！';
        exit;
        $admin = M("Admin");
        $data = $admin->create();
        
        $data['adm_password'] = md5($data['adm_password']);
        $data1['adm_name'] = $data['adm_name'];
        $data1['adm_password'] = $data['adm_password'];
        var_dump($data1);
        var_dump($data1['adm_password']);
        var_dump($admin->where("id=$data[id]")->save("$data"));
        //exit;
        //if(($data['adm_password']!='') && ($data['adm_name']!='')){
           /*  $rse = $admin->where("id=$data[id]")->save("$data1"); // 根据条件更新记录
            if($rse !== false) {
                $this->success('修改成功！','http://www.1qjx.com/admin.php?m=Home&c=Index&a=main');
            }else{
                $this->error('修改失败！','http://www.1qjx.com/admin.php?m=Home&c=Admin&a=admin');
            } */
        /* }else{
            $this->error('修改失败，请重新点击修改密码！','http://www.1qjx.com/admin.php?m=Home&c=Admin&a=admin');
        } */
        
        $this->display();
    }
    
    
    
    
}    
    
?>