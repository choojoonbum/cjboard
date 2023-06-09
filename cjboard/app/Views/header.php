<?php
$memberService = service('MemberService');
?>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="collapse navbar-collapse navbar-right">
            <ul class="nav navbar-nav">
                <li><a href="/">Home</a></li>
            <?php
            if ($memberService->isMember()) {
            ?>
                <li><a href="/member/logout">로그아웃</a></li>
                <li><a href="/mypage">마이페이지</a></li>
            <?php
            } else {
            ?>
                <li><a href="/member/login">로그인</a></li>
                <li><a href="/member/agreement">회원가입</a></li>
            <?php
            }
            ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="http://bootstrap.cjb:81/ch05/imgs/logo.png"></a>
            </div>
            <div id="navbar" class="collapse navbar-collapse ">
                <ul class="nav navbar-nav nav-main">
                    <li class="active"><a href="#">제품정보</a></li>
                    <li><a href="/community/lists/b-a-1">커뮤니티</a></li>
                    <li><a href="#">고객센터</a></li>
                    <li><a href="#">협력사</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

