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
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2"><?= esc(val('post_title', val('post', $view))); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="text-left">작성자 : <?= val('display_name', val('post', $view)); ?>  작성일 : <?= val('display_datetime', val('post', $view)); ?>   조회수 : <?= number_format(val('post_hit', val('post', $view))); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <?= val('content', val('post', $view)); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="text-center">이전글</th>
                    <td class="text-left"><a href="<?= val('url', val('prev_post', $view)); ?>"><?= val('post_title', val('prev_post', $view)); ?></a></td>
                </tr>
                <tr>
                    <th class="text-center">다음글</th>
                    <td class="text-left"><a href="<?= val('url', val('next_post', $view)); ?>"><?= val('post_title', val('next_post', $view)); ?></a></td>
                </tr>
            </tbody>
        </table>
    </div>

<?= $this->endSection() ?>