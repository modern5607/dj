
<style>
    .login_back { width:100%; height:400px; background:url(/rain_code1/_static/img/shattered.png); position:absolute; left:0; top:0; }
    .register_login { /*position:fixed; top:50%; left:58%; transform:translate(-58%,-58%); border:1px solid #ddd;*/ width:620px; height:235px; margin:250px auto; position:relative; top:0;
    box-shadow: 2px 3px 7px 4px #eee;
    -webkit-box-shadow: 2px 3px 7px 4px #eee;
    -moz-box-shadow:2px 3px 7px 4px #eee;
    }
    .register_login i { margin-right:5px; }
    .register_login input {  }
    .register_login header { padding:15px; background:#414350; color:#fff; text-align:center; font-size:1.3em;}
    .login_box { /*padding:20px;*/ background:#fff; text-align:center; width:310px; float:right;}
    .login_box::after{ display:block;visibility:hidden;clear:both;content:"" }
    .login_btn { text-align:center; margin-top:30px; padding: 10px 20px; border:0; background: rgb(39, 73, 146); color:#fff; }
    .login_btn:hover { background: rgb(38, 62, 114); }
    .register_login .hello { width:310px; height: 235px; float:left; background:url(/rain_code1/_static/img/login.JPG); }
    .register_login .hello h2 { font-size:2em; margin-top:150px; padding:10px 10px 0; color:#fff; }
    .register_login .hello h3 { padding: 0px 10px 10px; font-size:1.8em; color:#fff }

    .login_box table { margin:0 auto; width:80%; }
    .login_box table td { text-align:right; }
    .login_box table th { text-align:left; font-weight:300; }
    .login_box table .login_in { width:100%; padding:5px; border:0; border-bottom:1px solid #eee; margin-bottom:10px; }
    .login_box table .pw_in { width:100%; padding:5px; border:0; border-bottom:1px solid #eee}
</style>

<div class="register_login">
    <div class="hello">
        <h2>SMART FACTORY</h2>
        <h3>LOGIN</h3>
    </div>
    <div class="login_box">
    <header><h2><i class="material-icons">assignment_ind</i>로그인</h2></header>
	<form name="userloginform" action="<?php echo base_url('/register/login_exec');?>" method="post" style="margin-top:20px">
        <table>
            <tbody>
                <tr>
                    <!--<th><label for="ID">아이디</label></th>-->
                    <td><input type="text" name="ID" id="ID" value="" placeholder="아이디" class="login_in"/></td>
                </tr>
                <tr>
                    <!--<th><label for="PWD">비밀번호</label></th>-->
                    <td><input type="password" name="PWD" id="PWD" value="" placeholder="비밀번호" class="pw_in"/></td>
                </tr>
            </tbody>
        </table>
        <p>
			<input type="submit" value="로그인" class="login_btn" />
		</p>
	</form>
	</div>
</div>