<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="visual subtop_community">
    <div class="bt">커뮤니티</div>
    <div class="bottom">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#">공지사항</a></li>
            <li><a href="#">뉴스/이벤트/리뷰</a></li>
            <li><a href="#">사회공헌</a></li>
        </ul>
    </div>
</div>
<div class="container">
    <div class="title-area row">
        <div class="col-md-8 text-left">
            <h2>공지사항</h2>
        </div>
        <div class="col-md-4">
            <ol class="breadcrumb text-right">
                <li><a href="#">Home</a></li>
                <li><a href="#">커뮤니티 </a></li>
                <li class="active">공지사항</li>
            </ol>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="text-center">번 호</th>
            <th class="text-center">제 목</th>
            <th class="text-center">작성자</th>
            <th class="text-center">작성일</th>
            <th class="text-center">조회</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>2022년 추석 배송 및 고객센터 운영 안내</td>
            <td>홍길동</td>
            <td>22.09.02</td>
            <td>20</td>
        </tr>
        <tr>
            <td>1</td>
            <td>2022년 추석 배송 및 고객센터 운영 안내</td>
            <td>홍길동</td>
            <td>22.09.02</td>
            <td>20</td>
        </tr>
        <tr>
            <td>1</td>
            <td>2022년 추석 배송 및 고객센터 운영 안내</td>
            <td>홍길동</td>
            <td>22.09.02</td>
            <td>20</td>
        </tr>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>