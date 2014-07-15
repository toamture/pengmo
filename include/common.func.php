<?php
/**
 * 系统核心函数存放文件
 * @version        $Id: common.func.php 4 16:39 2010年7月6日Z tianya $
 * @package        DedeCMS.Libraries
 * @copyright      Copyright (c) 2007 - 2010, DesDev, Inc.
 * @license        http://help.dedecms.com/usersguide/license.html
 * @link           http://www.dedecms.com
 */
if(!defined('DEDEINC')) exit('dedecms');

/**
 *  载入小助手,系统默认载入小助手
 *  在/data/helper.inc.php中进行默认小助手初始化的设置
 *  使用示例:
 *      在开发中,首先需要创建一个小助手函数,目录在\include\helpers中
 *  例如,我们创建一个示例为test.helper.php,文件基本内容如下:
 *  <code>
 *  if ( ! function_exists('HelloDede'))
 *  {
 *      function HelloDede()
 *      {
 *          echo "Hello! Dede...";
 *      }
 *  }
 *  </code>
 *  则我们在开发中使用这个小助手的时候直接使用函数helper('test');初始化它
 *  然后在文件中就可以直接使用:HelloDede();来进行调用.
 *
 * @access    public
 * @param     mix   $helpers  小助手名称,可以是数组,可以是单个字符串
 * @return    void
 */
$_helpers = array();
function helper($helpers)
{
    //如果是数组,则进行递归操作
    if (is_array($helpers))
    {
        foreach($helpers as $dede)
        {
            helper($dede);
        }
        return;
    }

    if (isset($_helpers[$helpers]))
    {
        continue;
    }
    if (file_exists(DEDEINC.'/helpers/'.$helpers.'.helper.php'))
    { 
        include_once(DEDEINC.'/helpers/'.$helpers.'.helper.php');
        $_helpers[$helpers] = TRUE;
    }
    // 无法载入小助手
    if ( ! isset($_helpers[$helpers]))
    {
        exit('Unable to load the requested file: helpers/'.$helpers.'.helper.php');                
    }
}

/**
 *  控制器调用函数
 *
 * @access    public
 * @param     string  $ct    控制器
 * @param     string  $ac    操作事件
 * @param     string  $path  指定控制器所在目录
 * @return    string
 */
function RunApp($ct, $ac = '',$directory = '')
{
    
    $ct = preg_replace("/[^0-9a-z_]/i", '', $ct);
    $ac = preg_replace("/[^0-9a-z_]/i", '', $ac);
        
    $ac = empty ( $ac ) ? $ac = 'index' : $ac;
	if(!empty($directory)) $path = DEDECONTROL.'/'.$directory. '/' . $ct . '.php';
	else $path = DEDECONTROL . '/' . $ct . '.php';
        
	if (file_exists ( $path ))
	{
		require $path;
	} else {
		 if (DEBUG_LEVEL === TRUE)
        {
            trigger_error("Load Controller false!");
        }
        //生产环境中，找不到控制器的情况不需要记录日志
        else
        {
            header ( "location:/404.html" );
            die ();
        }
	}
	$action = 'ac_'.$ac;
    $loaderr = FALSE;
    $instance = new $ct ( );
    if (method_exists ( $instance, $action ) === TRUE)
    {
        $instance->$action();
        unset($instance);
    } else $loaderr = TRUE;
        
    if ($loaderr)
    {
        if (DEBUG_LEVEL === TRUE)
        {
            trigger_error("Load Method false!");
        }
        //生产环境中，找不到控制器的情况不需要记录日志
        else
        {
            header ( "location:/404.html" );
            die ();
        }
    }
}

/**
 *  载入小助手,这里用户可能载入用helps载入多个小助手
 *
 * @access    public
 * @param     string
 * @return    string
 */
function helpers($helpers)
{
    helper($helpers);
}

//兼容php4的file_put_contents
if(!function_exists('file_put_contents'))
{
    function file_put_contents($n, $d)
    {
        $f=@fopen($n, "w");
        if (!$f)
        {
            return FALSE;
        }
        else
        {
            fwrite($f, $d);
            fclose($f);
            return TRUE;
        }
    }
}

/**
 *  显示更新信息
 *
 * @return    void
 */
function UpdateStat()
{
    include_once(DEDEINC."/inc/inc_stat.php");
    return SpUpdateStat();
}

$arrs1 = array(0x63,0x66,0x67,0x5f,0x70,0x6f,0x77,0x65,0x72,0x62,0x79);
$arrs2 = array(0x20,0x3c,0x61,0x20,0x68,0x72,0x65,0x66,0x3d,0x68,0x74,0x74,0x70,0x3a,0x2f,0x2f,
0x77,0x77,0x77,0x2e,0x64,0x65,0x64,0x65,0x63,0x6d,0x73,0x2e,0x63,0x6f,0x6d,0x20,0x74,0x61,0x72,
0x67,0x65,0x74,0x3d,0x27,0x5f,0x62,0x6c,0x61,0x6e,0x6b,0x27,0x3e,0x50,0x6f,0x77,0x65,0x72,0x20,
0x62,0x79,0x20,0x44,0x65,0x64,0x65,0x43,0x6d,0x73,0x3c,0x2f,0x61,0x3e);

/**
 *  短消息函数,可以在某个动作处理后友好的提示信息
 *
 * @param     string  $msg      消息提示信息
 * @param     string  $gourl    跳转地址
 * @param     int     $onlymsg  仅显示信息
 * @param     int     $limittime  限制时间
 * @return    void
 */
function ShowMsg($msg, $gourl, $onlymsg=0, $limittime=0)
{
    if(empty($GLOBALS['cfg_plus_dir'])) $GLOBALS['cfg_plus_dir'] = '..';

    $htmlhead  = "<html>\r\n<head>\r\n<title>DedeCMS提示信息</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\" />\r\n";
    $htmlhead .= "<base target='_self'/>\r\n<style>div{line-height:160%;}</style></head>\r\n<body leftmargin='0' topmargin='0' bgcolor='#FFFFFF'>".(isset($GLOBALS['ucsynlogin']) ? $GLOBALS['ucsynlogin'] : '')."\r\n<center>\r\n<script>\r\n";
    $htmlfoot  = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

    $litime = ($limittime==0 ? 1000 : $limittime);
    $func = '';

    if($gourl=='-1')
    {
        if($limittime==0) $litime = 5000;
        $gourl = "javascript:history.go(-1);";
    }

    if($gourl=='' || $onlymsg==1)
    {
        $msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
    }
    else
    {
        //当网址为:close::objname 时, 关闭父框架的id=objname元素
        if(preg_match('/close::/',$gourl))
        {
            $tgobj = trim(preg_replace('/close::/', '', $gourl));
            $gourl = 'javascript:;';
            $func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';\r\n";
        }
        
        $func .= "      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='$gourl'; pgo=1; }
      }\r\n";
        $rmsg = $func;
        $rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #DADADA;'>";
        $rmsg .= "<div style='padding:6px;font-size:12px;border-bottom:1px solid #DADADA;background:#DBEEBD url({$GLOBALS['cfg_plus_dir']}/img/wbg.gif)';'><b>DedeCMS 提示信息！</b></div>\");\r\n";
        $rmsg .= "document.write(\"<div style='height:130px;font-size:10pt;background:#ffffff'><br />\");\r\n";
        $rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");\r\n";
        $rmsg .= "document.write(\"";
        
        if($onlymsg==0)
        {
            if( $gourl != 'javascript:;' && $gourl != '')
            {
                $rmsg .= "<br /><a href='{$gourl}'>如果你的浏览器没反应，请点击这里...</a>";
                $rmsg .= "<br/></div>\");\r\n";
                $rmsg .= "setTimeout('JumpUrl()',$litime);";
            }
            else
            {
                $rmsg .= "<br/></div>\");\r\n";
            }
        }
        else
        {
            $rmsg .= "<br/><br/></div>\");\r\n";
        }
        $msg  = $htmlhead.$rmsg.$htmlfoot;
    }
    echo $msg;
}

/*
add by kaiser

*/
function ShowMsg_web($msg, $gourl, $onlymsg=0, $limittime=0)
{
	
    if(empty($GLOBALS['cfg_plus_dir'])) $GLOBALS['cfg_plus_dir'] = '..';

    $htmlhead  = '<!DOCTYPE html><!--[if lt IE 7 ]><html class="ie ie6" lang="zh"> <![endif]--><!--[if IE 7 ]><html class="ie ie7" lang="zh"> <![endif]--><!--[if IE 8 ]><html class="ie ie8" lang="zh"> <![endif]--><!--[if (gte IE 9)|!(IE)]><!--><html lang="zh"> <!--<![endif]--><head><meta name="viewport" content="initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no" /><link rel="shortcut icon" href="/demand/images/favicon.ico" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/demand/images/apple-touch-icon-144-precomposed.png" /><link rel="apple-touch-icon-precomposed" sizes="114x114" href="/demand/images/apple-touch-icon-114-precomposed.png" /><link rel="apple-touch-icon-precomposed" sizes="72x72" href="/demand/images/apple-touch-icon-72-precomposed.png" /><link rel="apple-touch-icon-precomposed" href="/demand/images/apple-touch-icon-57-precomposed.png" /><title>提示信息</title><link href="/demand/css/bootstrap.css" type="text/css" rel="stylesheet" /><link href="/demand/css/style.css" type="text/css" rel="stylesheet" /><link href="/demand/css/prettyPhoto.css" type="text/css" rel="stylesheet" /><link href="/demand/css/font-icomoon.css" type="text/css" rel="stylesheet" /><link href="/demand/css/font-awesome.css" type="text/css" rel="stylesheet" />';
    $htmlhead .= '<!--[if IE 7]><link rel="stylesheet" href="/demand/assets/css/font-awesome-ie7.css"/><![endif]--><script type="text/javascript" src="/demand/js/jquery.min.js"></script><script type="text/javascript" src="/demand/js/bootstrap.min.js"></script><script type="text/javascript" src="/demand/js/jquery.easing.1.3.js"></script><script type="text/javascript" src="/demand/js/jquery.quicksand.js"></script><script type="text/javascript" src="/demand/js/superfish.js"></script><script type="text/javascript" src="/demand/js/hoverIntent.js"></script><script type="text/javascript" src="/demand/js/jquery.flexslider.js"></script><script type="text/javascript" src="/demand/js/jflickrfeed.min.js"></script><script type="text/javascript" src="/demand/js/jquery.prettyPhoto.js"></script><script type="text/javascript" src="/demand/js/jquery.elastislide.js"></script><script type="text/javascript" src="/demand/js/jquery.tweet.js"></script><script type="text/javascript" src="/demand/js/smoothscroll.js"></script><script type="text/javascript" src="/demand/js/jquery.ui.totop.js"></script><script type="text/javascript" src="/demand/js/main.js"></script><script type="text/javascript" src="/demand/js/ajax-mail.js"></script><!--[if lt IE 9]><script type="text/javascript" src="/demand/http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]--><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body><!--top menu--><section id="top-menu"></section><!--header--><header id="header"><div class="container"><div class="row header-top"><div class="span5 logo"><a class="logo-img" href="/" title="responsive template"><img src="/demand/example/logo.png" alt="Tabulate" /></a><p class="tagline">Responsive Website Template</p></div><div class="span7 social-container"><p class="phone hidden-phone"><i class="icon-envelope"></i> 87068928@qq.com</p><p class="phone hidden-phone"><i class="icon-bell"></i> Call Us 153 0982 0327</p><div class="top-social"><a data-original-title="QQ" rel="tooltip" data-placement="top" class="qq" href="http://wpa.qq.com/msgrd?v=3&uin=2425553510&site=qq&menu=yes" target="_blank"></a></div></div></div><div class="row header-nav"><div class="span12"><nav id="menu" class="clearfix"><ul><li class="current"><a href="/"><span class="name">首页</span></a></li><li><a href="/pengmo/dynamic"><span class="name">工作室动态</span></a></li><li><a href="/pengmo/template"><span class="name">模板展示</span></a></li><li><a href="/pengmo/services"><span class="name">服务项目</span></a></li><li><a href="/pengmo/price"><span class="name">价格列表</span></a></li><li><a href="/pengmo/about"><span class="name">关于我们</span></a></li><li><a href="/pengmo/contact"><span class="name">联系我们</span></a></li></ul></nav><!--<form class="top-search pull-right">--><!--<input type="text" placeholder="text here..." class="span3">--><!--<button type="button" class="btn"><i class="icon-search-form"></i></button>--><!--</form>--></div></div></div></header>';
	$htmlhead .= '<section class="breadcrumbs"><div class="container"><div class="page-header"><div class="row"><div class="span8"><h1>Tips<small> :提示信息</small></h1><div><a href="/">首页</a> &nbsp;&rsaquo;&nbsp;提示信息</div></div></div></div></div></section>';
    $htmlfoot  = '<!--footer--><footer id="footer"><div class="container"><div class="row"><div class="span4"><p><img src="/demand/example/logo.png" alt="" /></p><address><p><i class="icon-map-marker"></i> 大连市沙河口区新生路23号</p><p><i class="icon-phone"></i> 116031</p><p><i class="icon-mobile-2"></i> 153 0982 0327</p><p><i class="icon-mail-3"></i> <a href="/demand/mailto:#">87068928@qq.com</a></p></address></div><div class="span8"><div class="row"><div class="span8"></div><div class="span8"></div></div></div><div class="span4"><p class="heading">关于我们</p><p>您的肯定，就是我们最大的动力！欢迎加入我们！</p><p class="heading">关注我们</p><p>请输入您的邮箱地址，我们将实时为您提供工作室的最新动态。</p><div class="input-append"><input type="text" placeholder="输入邮箱地址" class="span2" id="foot_email" /><button type="button" class="btn btn-inverse" onClick="chose()">订阅</button></div></div><div class="span4"><p class="heading">鹏魔工作室</p><ul class="footer-navigate"><li><a href="/">首页</a></li><li><a href="/pengmo/dynamic">工作室动态</a></li><li><a href="/pengmo/template">模板展示</a></li><li><a href="/pengmo/price">价格列表</a></li><li><a href="/pengmo/about">关于我们</a></li></ul></div></div></div></footer><!--footer menu--><section id="footer-menu"><div class="container"><div class="row"><div class="span4"><p class="copyright">Copyright &copy; 2014.Company name All rights reserved.鹏魔工作室</p></div><div class="span8 hidden-phone"><ul class="pull-right"><li><a href="/demand/#">Privacy Policy</a></li><li><a href="/pengmo/contact">Contact Us</a></li><li><a href="/demand/#">Sitemap</a></li></ul></div></div></div></section><div style="display:none"><script src="http://s4.cnzz.com/z_stat.php?id=1000439680&web_id=1000439680" language="JavaScript"></script></div><script>function chose(){var email = $("#foot_email").val();if(email != ""){var url = "/pengmo/contact?email="+escape(email);location.href=url;}else{alert("请输入您的邮箱地址！");}}</script></body></html>';

    $litime = ($limittime==0 ? 1000 : $limittime);
    $func = '';

    if($gourl=='-1')
    {
        if($limittime==0) $litime = 5000;
        $gourl = "javascript:history.go(-1);";
    }

    if($gourl=='' || $onlymsg==1)
    {
        $msg = "<script>alert(\"".str_replace("\"","“",$msg)."\");</script>";
    }
    else
    {
        //当网址为:close::objname 时, 关闭父框架的id=objname元素
        if(preg_match('/close::/',$gourl))
        {
            $tgobj = trim(preg_replace('/close::/', '', $gourl));
            $gourl = 'javascript:;';
            $func .= "window.parent.document.getElementById('{$tgobj}').style.display='none';";
        }
        
        $func .= "      var pgo=0;
      function JumpUrl(){
        if(pgo==0){ location='$gourl'; pgo=1; }
      }";
        $rmsg = '<section id="container"><div class="container" style=" margin-bottom:50px;"><div class="row"><div class="span12 pull-center"><h2>Sorry!</h2><p>'.str_replace("\"","“",$msg).'<br /><br /><a href="'.$gourl.'">返回上一页，请点击这里...</a></p><div class="spacer"></div><p><a class="btn btn-large btn-welcome" href="/">返回首页</a></p></div></div></div></section>';
        //$rmsg .= "document.write(\"<br /><div style='width:450px;padding:0px;border:1px solid #DADADA;'>";
        //$rmsg .= "<div style='padding:6px;font-size:12px;border-bottom:1px solid #DADADA;background:#DBEEBD url({$GLOBALS['cfg_plus_dir']}/img/wbg.gif)';'><b>DedeCMS 提示信息！</b></div>\");";
        //$rmsg .= "document.write(\"<div style='height:130px;font-size:10pt;background:#ffffff'><br />\");";
        //$rmsg .= "document.write(\"".str_replace("\"","“",$msg)."\");";
        //$rmsg .= "document.write(\"";
        
        /*if($onlymsg==0)
        {
            if( $gourl != 'javascript:;' && $gourl != '')
            {
                $rmsg .= "<br /><a href='{$gourl}'>如果你的浏览器没反应，请点击这里...</a>";
                $rmsg .= "<br/></div>\");";
                $rmsg .= "setTimeout('JumpUrl()',$litime);";
            }
            else
            {
                $rmsg .= "<br/></div>\");";
            }
        }
        else
        {
            $rmsg .= "<br/><br/></div>\");";
        }*/
        $msg  = $htmlhead.$rmsg.$htmlfoot;
    }
    echo $msg;
}

/**
 *  获取验证码的session值
 *
 * @return    string
 */
function GetCkVdValue()
{
	@session_id($_COOKIE['PHPSESSID']);
    @session_start();
    return isset($_SESSION['securimage_code_value']) ? $_SESSION['securimage_code_value'] : '';
}

/**
 *  PHP某些版本有Bug，不能在同一作用域中同时读session并改注销它，因此调用后需执行本函数
 *
 * @return    void
 */
function ResetVdValue()
{
    @session_start();
    $_SESSION['securimage_code_value'] = '';
}


// 自定义函数接口
// 这里主要兼容早期的用户扩展,v5.7之后我们建议使用小助手helper进行扩展
if( file_exists(DEDEINC.'/extend.func.php') )
{
    require_once(DEDEINC.'/extend.func.php');
}