<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    <title>极验行为式验证 php 类网站安装测试页面</title>
</head>
<body>
<style type="text/css">

    .container{
        width: 960px;
        margin: 0 auto;
    }
    .content{
        width: 960px;
        margin: 10 auto;
        border-top: 1px solid #ccc;

    }
    .box{
        width:300px;
        margin: 30px auto;
    }
    .header{
        margin: 80px auto 30px auto;
        text-align: center;
        font-size: 34px;
    }
    input{
        width: 200px;
        padding: 6px 9px;
    }
    button{
        cursor: pointer;
        line-height: 35px;
        width: 110px;
        margin:30px 0 0 90px;
        border: 1px solid #FFFFF0;
        background-color: #31C552;

        border-radius: 4px;
        font-size: 14px;
        color: #FFFFF0;
    }
</style>

<div class="container">
    @if (Session::has('flash_notification.message'))
        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            {{ Session::get('flash_notification.message') }}
        </div>
    @endif
    <div class="header">
        极验行为式验证 php 类网站安装测试页面
    </div>
    <div class="content">
        <form method="post" action="">
            <input name="_token" value="{{ csrf_token() }}" type="hidden"/>
            <div class="box">
                <label>邮箱：</label>
                <input type="text" name="email" value="geetest@geetest.com"/>
            </div>
            <div class="box">
                <label>密码：</label>
                <input type="password" name="password" value="geetest"/>
            </div>
            <div class="box" id="div_geetest_lib">
                <div id="div_id_embed"></div>
                <script type="text/javascript">

                    var gtFailbackFrontInitial = function(result) {
                        var s = document.createElement('script');
                        s.id = 'gt_lib';
                        s.src = 'http://static.geetest.com/static/js/geetest.0.0.0.js';
                        s.charset = 'UTF-8';
                        s.type = 'text/javascript';
                        document.getElementsByTagName('head')[0].appendChild(s);
                        var loaded = false;
                        s.onload = s.onreadystatechange = function() {
                            if (!loaded && (!this.readyState|| this.readyState === 'loaded' || this.readyState === 'complete')) {
                                loadGeetest(result);
                                loaded = true;
                            }
                        };
                    }
                    //get  geetest server status, use the failback solution


                    var loadGeetest = function(config) {

                        //1. use geetest capthca
                        window.gt_captcha_obj = new window.Geetest({
                            gt : config.gt,
                            challenge : config.challenge,
                            product : 'embed',
                            offline : !config.success
                        });

                        gt_captcha_obj.appendTo("#div_id_embed");
                    }

                    s = document.createElement('script');
                    s.src = 'http://api.geetest.com/get.php?callback=gtcallback';
                    $("#div_geetest_lib").append(s);

                    var gtcallback =( function() {
                        var status = 0, result, apiFail;
                        return function(r) {
                            status += 1;
                            if (r) {
                                result = r;
                                setTimeout(function() {
                                    if (!window.Geetest) {
                                        apiFail = true;
                                        gtFailbackFrontInitial(result)
                                    }
                                }, 1000)
                            }
                            else if(apiFail) {
                                return
                            }
                            if (status == 2) {
                                loadGeetest(result);
                            }
                        }
                    })()

                    $.ajax({
                        url : "{{ url('verify') }}?rand="+Math.round(Math.random()*100),
                        type : "get",
                        dataType : 'JSON',
                        success : function(result) {
                            console.log(result);
                            gtcallback(result)
                        }
                    })
                </script>
            </div>
            <div class="box">
                <button id="submit_button">提交</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>