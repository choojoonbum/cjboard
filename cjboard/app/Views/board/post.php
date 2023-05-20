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
                <?php
                if (val('link_count', $view) > 0) {
                    foreach (val('link', $view) as $key => $value) {
                        ?>
                        <tr>
                            <td colspan="2" class="text-left"><i class="fa fa-link"></i> <a href="<?php echo val('link_link', $value); ?>" target="_blank"><?php echo esc(val('pln_url', $value)); ?></a><span class="badge"><?php echo number_format(val('pln_hit', $value)); ?></span>
                                <?php if (val('show_url_qrcode', val('board', $view))) { ?>
                                    <span class="url-qrcode" data-qrcode-url="<?php echo urlencode(val('pln_url', $value)); ?>"><i class="fa fa-qrcode"></i></span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <tr>
                    <td colspan="2">
                        <div class="contents-view-img">
                            <?php
                            if (val('file_image', $view)) {
                                foreach (val('file_image', $view) as $key => $value) {
                                    ?>
                                    <img src="/imageRender<?php echo val('origin_image_url', $value); ?>" alt="<?php echo esc(val('pfi_originname', $value)); ?>" title="<?php echo esc(val('pfi_originname', $value)); ?>" class="view_full_image" data-origin-image-url="<?php echo val('origin_image_url', $value); ?>" style="max-width:100%;" />
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="text-left">
                            <?= val('content', val('post', $view)); ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="text-center px150">이전글</th>
                    <td class="text-left"><a href="<?= val('url', val('prev_post', $view)); ?>"><?= val('post_title', val('prev_post', $view)); ?></a></td>
                </tr>
                <tr>
                    <th class="text-center px150">다음글</th>
                    <td class="text-left"><a href="<?= val('url', val('next_post', $view)); ?>"><?= val('post_title', val('next_post', $view)); ?></a></td>
                </tr>
            </tbody>
        </table>
    </div>

<?= $this->endSection() ?>