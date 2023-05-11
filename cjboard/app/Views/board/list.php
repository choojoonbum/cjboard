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
            <?php
			if (val('list', val('data', val('list', $view)))) {
				foreach (val('list', val('data', val('list', $view))) as $result) {
			?>
                    <tr>
                        <?php if (val('is_admin', $view)) { ?><th scope="row"><input type="checkbox" name="chk_post_id[]" value="<?php echo val('post_id', $result); ?>" /></th><?php } ?>
                        <td><?php echo val('num', $result); ?></td>
                        <td>
                            <?php if (val('category', $result)) { ?><a href="<?php echo board_url(val('brd_key', val('board', val('list', $view)))); ?>?category_id=<?php echo esc(val('bca_key', val('category', $result))); ?>"><span class="label label-default"><?php echo esc(val('bca_value', val('category', $result))); ?></span></a><?php } ?>
                            <?php if (val('post_reply', $result)) { ?><span class="label label-primary" style="margin-left:<?php echo strlen(val('post_reply', $result)) * 10; ?>px">Re</span><?php } ?>
                            <a href="<?php echo val('post_url', $result); ?>" style="
                            <?php
                            if (val('title_color', $result)) {
                                echo 'color:' . val('title_color', $result) . ';';
                            }
                            if (val('title_font', $result)) {
                                echo 'font-family:' . val('title_font', $result) . ';';
                            }
                            if (val('title_bold', $result)) {
                                echo 'font-weight:bold;';
                            }
                            if (val('post_id', val('post', $view)) === val('post_id', $result)) {
                                echo 'font-weight:bold;';
                            }
                            ?>
                                    " title="<?php echo esc(val('title', $result)); ?>"><?php echo esc(val('title', $result)); ?></a>
                            <?php if (val('is_mobile', $result)) { ?><span class="fa fa-wifi"></span><?php } ?>
                            <?php if (val('post_file', $result)) { ?><span class="fa fa-download"></span><?php } ?>
                            <?php if (val('post_secret', $result)) { ?><span class="fa fa-lock"></span><?php } ?>
                            <?php if (val('is_hot', $result)) { ?><span class="label label-danger">Hot</span><?php } ?>
                            <?php if (val('is_new', $result)) { ?><span class="label label-warning">New</span><?php } ?>
                            <?php if (val('ppo_id', $result)) { ?><i class="fa fa-bar-chart"></i><?php } ?>
                            <?php if (val('post_comment_count', $result)) { ?><span class="label label-warning">+<?php echo val('post_comment_count', $result); ?></span><?php } ?>
                        <td><?php echo val('display_name', $result); ?></td>
                        <td><?php echo val('display_datetime', $result); ?></td>
                        <td><?php echo number_format(val('post_hit', $result)); ?></td>
                    </tr>
                    <?php
                }
            }
            if ( ! val('notice_list', val('list', $view)) && ! val('list', val('data', val('list', $view)))) {
                ?>
                    <tr>
                        <td colspan="5">게시물이 없습니다</td>
                    </tr>
            <?php } ?>

            </tbody>
        </table>
        <?php if (val('write_url', val('list', $view))) { ?>
            <div class="pull-right">
                <a href="<?php echo val('write_url', val('list', $view)); ?>" class="btn btn-primary">글쓰기</a>
            </div>
        <?php } ?>
        <nav><?php echo val('paging', val('list', $view)); ?></nav>
    </div>

<?= $this->endSection() ?>