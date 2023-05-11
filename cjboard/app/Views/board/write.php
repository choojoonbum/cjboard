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
    <div class="panel panel-primary">
        <div class="panel-heading"><?php echo esc(val('board_name', val('board', $view))); ?> 글쓰기</div>
        <div class="panel-body">
            <?php
            $attributes = array('class' => 'form-horizontal', 'name' => 'fwrite', 'id' => 'fwrite', 'onsubmit' => 'return submitContents(this)');
            echo form_open_multipart('', $attributes);
            echo btAlert(session()->getFlashdata('message'));
            if (empty($errors)) $errors = [];
            ?>
            <input type="hidden" name="<?php echo val('primary_key', $view); ?>"	value="<?php echo val(val('primary_key', $view), val('post', $view)); ?>" />
            <div class="form-horizontal box-table">
                <?php if (val('is_post_name', val('post', $view))) { ?>
                    <div class="form-group <?php if (val('post_nickname', $errors)) { ?>has-error<?php } ?>">
                        <label for="post_nickname" class="col-md-2 control-label">이름</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control px150" name="post_nickname" id="post_nickname" value="<?php echo set_value('post_nickname', val('post_nickname', val('post', $view))); ?>" />
                            <?php if (val('captcha_key', $errors)) { ?>
                                <div class="text-danger text-left"><?= val('post_nickname', $errors) ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if (service('memberService')->isMember() === false) { ?>
                        <div class="form-group <?php if (val('post_password', $errors)) { ?>has-error<?php } ?>">
                            <label for="post_password" class="col-md-2 control-label">비밀번호</label>
                            <div class="col-md-10">
                                <input type="password" class="form-control px150" name="post_password" id="post_password" />
                                <?php if (val('captcha_key', $errors)) { ?>
                                    <div class="text-danger text-left"><?= val('post_password', $errors) ?></div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group <?php if (val('post_email', $errors)) { ?>has-error<?php } ?>">
                        <label for="post_email" class="col-md-2 control-label">이메일</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="post_email" id="post_email" value="<?php echo set_value('post_email', val('post_email', val('post', $view))); ?>" />
                            <?php if (val('captcha_key', $errors)) { ?>
                                <div class="text-danger text-left"><?= val('post_email', $errors) ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group <?php if (val('post_homepage', $errors)) { ?>has-error<?php } ?>">
                        <label for="post_homepage" class="col-md-2 control-label">홈페이지</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="post_homepage" id="post_homepage" value="<?php echo set_value('post_homepage', val('post_homepage', val('post', $view))); ?>" />
                            <?php if (val('captcha_key', $errors)) { ?>
                                <div class="text-danger text-left"><?= val('post_homepage', $errors) ?></div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group <?php if (val('post_title', $errors)) { ?>has-error<?php } ?>">
                    <label for="post_title" class="col-md-2 control-label">제목</label>
                    <div class="col-md-10" style="display:table;">
                        <input type="text" class="form-control" name="post_title" id="post_title" value="<?php echo set_value('post_title', val('post_title', val('post', $view))); ?>" />
                        <?php if (val('captcha_key', $errors)) { ?>
                            <div class="text-danger text-left"><?= val('post_title', $errors) ?></div>
                        <?php } ?>
                        <?php if (val('use_google_map', val('board', $view))) { ?>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-md btn-default" id="btn_google_map" onClick="open_google_map();">지도</button>
                            </span>
                        <?php } ?>
                    </div>
                </div>

                <?php if (val('use_category', val('board', $view))) { ?>
                    <div class="form-group">
                        <label class="col-md-2 control-label">분류</label>
                        <div class="col-md-10">
                            <div class="form-inline">
                                <select name="post_category" class="form-control">
                                    <option value="">카테고리선택</option>
                                    <?php
                                    $category = val('category', $view);
                                    function ca_select($p = '', $category = '', $post_category = '')
                                    {
                                        $return = '';
                                        if ($p && is_array($p)) {
                                            foreach ($p as $result) {
                                                $exp = explode('.', val('bca_key', $result));
                                                $len = (val(1, $exp)) ? strlen(val(1, $exp)) : 0;
                                                $space = str_repeat('-', $len);
                                                $return .= '<option value="' . esc(val('bca_key', $result)) . '"';
                                                if (val('bca_key', $result) === $post_category) {
                                                    $return .= 'selected="selected"';
                                                }
                                                $return .= '>' . $space . esc(val('bca_value', $result)) . '</option>';
                                                $parent = val('bca_key', $result);
                                                $return .= ca_select(val($parent, $category), $category, $post_category);
                                            }
                                        }
                                        return $return;
                                    }

                                    echo ca_select(val(0, $category), $category, val('post_category', val('post', $view)));
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <div class="col-sm-12">
                        <?php if ( ! val('use_dhtml', val('board', $view))) { ?>
                            <div>
                                <div class="btn-group pull-right mb10">
                                    <?php if (val('use_emoticon', val('board', $view))) { ?>
                                        <button type="button" class="btn btn-default btn-sm" onclick="window.open('<?php echo site_url('helptool/emoticon?id=post_content'); ?>', 'emoticon', 'width=600,height=400,scrollbars=yes')"><i class="fa fa-smile-o fa-lg"></i></button>
                                    <?php } ?>
                                    <?php if (val('use_specialchars', val('board', $view))) { ?>
                                        <button type="button" class="btn btn-default btn-sm" onclick="window.open('<?php echo site_url('helptool/specialchars?id=post_content'); ?>', 'specialchars', 'width=490,height=245,scrollbars=yes')"><i class="fa fa-star-o fa-lg"></i></button>
                                    <?php } ?>
                                    <button type="button" class="btn btn-default btn-sm" onClick="resize_textarea('post_content', 'down');"><i class="fa fa-plus fa-lg"></i></button>
                                    <button type="button" class="btn btn-default btn-sm" onClick="resize_textarea('post_content', 'up');"><i class="fa fa-minus fa-lg"></i></button>
                                </div>
                            </div>
                        <?php } ?>

                        <?php echo display_dhtml_editor('post_content', set_value('post_content', val('post_content', val('post', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = val('use_dhtml', val('board', $view)), $editor_type = config_item_db('post_editor_type')); ?>

                    </div>
                </div>

                <?php
                if (val('link_count', val('board', $view)) > 0) {
                    $link_count = val('link_count', val('board', $view));
                    for ($i = 0; $i < $link_count; $i++) {
                        $link = esc(val('pln_url', val($i, val('link', $view))));
                        $link_column = $link ? 'post_link_update[' . val('pln_id', val($i, val('link', $view))) . ']' : 'post_link[' . $i . ']';
                        ?>
                        <div class="form-group <?php if (val($link_column, $errors)) { ?>has-error<?php } ?>">
                            <label for="<?php echo $link_column; ?>" class="col-md-2 control-label">링크 #<?php echo $i+1; ?></label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="<?php echo $link_column; ?>" id="<?php echo $link_column; ?>" value="<?php echo set_value($link_column, $link); ?>" />
                            </div>
                        </div>
                        <?php
                    }
                }
                if (val('use_upload', val('board', $view))) {
                    $file_count = val('upload_file_count', val('board', $view));
                    for ($i = 0; $i < $file_count; $i++) {
                        $download_link = esc(val('download_link', val($i, val('file', $view))));
                        $file_column = $download_link ? 'post_file_update[' . val('pfi_id', val($i, val('file', $view))) . ']' : 'post_file[' . $i . ']';
                        $del_column = $download_link ? 'post_file_del[' . val('pfi_id', val($i, val('file', $view))) . ']' : '';
                        ?>
                        <div class="form-group <?php if (val($del_column, $errors)) { ?>has-error<?php } ?>">
                            <label for="<?php echo $file_column; ?>" class="col-md-2 control-label">파일 #<?php echo $i+1; ?></label>
                            <div class="col-md-10">
                                <input type="file" class="form-control" name="<?php echo $file_column; ?>" id="<?php echo $file_column; ?>" />
                                <?php if ($download_link) { ?>
                                    <a href="<?php echo $download_link; ?>"><?php echo esc(val('pfi_originname', val($i, val('file', $view)))); ?></a>
                                    <label for="<?php echo $del_column; ?>">
                                        <input type="checkbox" name="<?php echo $del_column; ?>" id="<?php echo $del_column; ?>" value="1" <?php echo set_checkbox($del_column, '1'); ?> /> 삭제
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <?php if (val('use_post_tag', val('board', $view)) && val('can_tag_write', val('board', $view))) { ?>
                    <div class="form-group <?php if (val('post_tag', $errors)) { ?>has-error<?php } ?>">
                        <label for="post_tag" class="col-md-2 control-label">태그</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="post_tag" id="post_tag" value="<?php echo set_value('post_tag', val('post_tag', val('post', $view))); ?>" /> <div class="help-block">태그를 콤마(,)로 구분해 입력해주세요. 예) 자유,인기,질문</div>
                        </div>
                    </div>
                <?php } ?>
                <div class="form-group <?php if (val('captcha_key', $errors)) { ?>has-error<?php } ?>">
                    <label class="col-md-3 control-label"><img src="<?= base_url('captcha') ?>" width="160" height="40" id="captcha" alt="captcha" title="captcha" /></label>
                    <div class="col-md-9 text-left">
                        <input type="text" name="captcha_key" id="captcha_key" class="form-control" value="" />
                        <?php if (val('captcha_key', $errors)) { ?>
                            <div class="text-danger text-left"><?= val('captcha_key', $errors) ?></div>
                        <?php } ?>
                        <p class="help-block">좌측에 보이는 문자를 입력해주세요</p>
                    </div>
                </div>
                <div class="border_button text-center mt20">
                    <button type="button" class="btn btn-danger btn-history-back">취소</button>
                    <button type="submit" class="btn btn-primary">작성완료</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>