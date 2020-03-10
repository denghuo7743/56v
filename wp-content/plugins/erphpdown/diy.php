<?php
    function showMsg($msg, $pid=0){
?>
    <html lang="zh-CN">
		<head>
			<meta charset="UTF-8" />
				<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />  <title>文件下载 - <?php echo get_the_title($pid);?> - <?php bloginfo('name');?></title>
		</head>

        <body class="erphpdown-body">
			<div id="erphpdown-download">
				<a style="display: block; text-indent: 0em;" href="https://www.wu6v.com/"><img src="https://www.wu6v.com/wp-content/uploads/2019/06/dlogo-1.png" alt="" /></a>
				<div class="title">
					<span>网站分享资源信息</span>
				</div>
				<p><span style="font-weight: bold;">资源名称：</span><a href="<?php echo esc_url( get_permalink($pid) ) ?>"><?php echo get_the_title($pid);?></a></p>
				
				<!-- 以下内容不要动 -->
        		<div class="msg"><?php echo $msg;?></div>
                <!-- 以上内容不要动 -->
				<div class="title">
					<span>网站分享资源须知</span>
				</div>
					<ul style="text-indent: 0em;" >
						<li>默认解压密码：wu6v.com</li>
						<li>如遇资源下载链接失效，请与站长联系</li>
					</ul>
				<div class="title">
					<span>网站分享资源声明</span>
				</div>
					<p>本站多数资源为网络收集整理，部分资源为站长原创内容，版权及最终解释权归资源原作者所有。本站分享的所有资源均为学习交流使用，切勿用作商业用途，请于资源下载24小时后自行删除。若确实需要用作商业用途请自行与资源原作者联系，获取授权后方可使用，未经授权私自用做商业用途的，请自行承担法律后果，本站概不负责。本站发布的任何内容如涉及到您的权益，请速与站长联系解决，我们会在第一时间与您协商解决。</p>
			</div>
			<div id="erphpdown-download-copyright">
				&copy;<?php echo date("Y")." <a href='".get_bloginfo("url")."'>".get_bloginfo("name")."</a>";?>
			</div>
		</body>
	</html>
	<?php
	exit;
	}