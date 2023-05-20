<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">회원 가입</div>
        <div class="panel-body">
            <?php
            $attributes = array('class' => 'form-horizontal', 'name' => 'formRegister', 'id' => 'formRegister');
            echo form_open_multipart('', $attributes);
            echo btAlert(session()->getFlashdata('message'));
            if (empty($errors)) $errors = [];
            foreach (val('html_content', $view) as $key => $value) {
                ?>
                <div class="form-group <?php if (val(val('field_name', $value), $errors)) { ?>has-error<?php } ?>">
                    <label class="col-md-3 control-label" for="<?= val('field_name', $value); ?>"><?= val('display_name', $value); ?></label>
                    <div class="col-md-9">
                        <?= val('input', $value); ?>
                        <?php if (val(val('field_name', $value), $errors)) { ?>
                            <div class="text-danger text-left"><?= val(val('field_name', $value), $errors); ?></div>
                        <?php } ?>
                    </div>
                </div>
                <?php
            }
            if (config_item_db('use_member_photo') && config_item_db('member_photo_width') > 0 && config_item_db('member_photo_height') > 0) {
                ?>
                <div class="form-group <?php if (val('mem_photo', $errors)) { ?>has-error<?php } ?>">
                    <label class="col-md-3 control-label" for="mem_photo">프로필사진</label>
                    <div class="col-md-9 text-left">
                        <input type="file" name="mem_photo" id="mem_photo" />
                        <?php if (val('mem_photo', $errors)) { ?>
                            <div class="text-danger text-left"><?= val('mem_photo', $errors) ?></div>
                        <?php } ?>
                        <p class="help-block">가로길이 : <?= number_format(config_item_db('member_photo_width')); ?>px, 세로길이 : <?= number_format(config_item_db('member_photo_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                    </div>
                </div>
                <?php
            }
            if (config_item_db('use_member_icon') && config_item_db('member_icon_width') > 0 && config_item_db('member_icon_height') > 0) {
                ?>
                <div class="form-group <?php if (val('mem_icon', $errors)) { ?>has-error<?php } ?>">
                    <label class="col-md-3 control-label" for="mem_icon">회원아이콘</label>
                    <div class="col-md-9 text-left">
                        <input type="file" name="mem_icon" id="mem_icon" />
                        <?php if (val('mem_icon', $errors)) { ?>
                            <div class="text-danger text-left"><?= val('mem_icon', $errors) ?></div>
                        <?php } ?>
                        <p class="help-block">가로길이 : <?= number_format(config_item_db('member_icon_width')); ?>px, 세로길이 : <?= number_format(config_item_db('member_icon_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <label class="col-md-3 control-label" for="mem_open_profile">정보공개</label>
                <div class="col-md-9 text-left">
                    <div class="checkbox">
                        <label for="mem_open_profile">
                            <input type="checkbox" name="mem_open_profile" id="mem_open_profile" value="1" <?= set_checkbox('mem_open_profile', '1', true); ?> />
                            다른분들이 나의 정보를 볼 수 있도록 합니다.
                        </label>
                        <?php
                        if (val('open_profile_description', $view)) {
                            ?>
                            <p class="help-block"><?= val('open_profile_description', $view); ?></p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if (config_item_db('use_note')) {
                ?>
                <div class="form-group">
                    <label class="col-md-3 control-label">쪽지기능사용</label>
                    <div class="col-md-9 text-left">
                        <div class="checkbox">
                            <label for="mem_use_note">
                                <input type="checkbox" name="mem_use_note" id="mem_use_note" value="1" <?= set_checkbox('mem_use_note', '1', true); ?> />
                                쪽지를 주고 받을 수 있습니다.
                            </label>
                            <?php if (val('use_note_description', $view)) { ?>
                                <p class="help-block"><?= val('use_note_description', $view); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="form-group">
                <label class="col-md-3 control-label">이메일 수신여부</label>
                <div class="col-md-9 text-left">
                    <div class="checkbox">
                        <label for="mem_receive_email" >
                            <input type="checkbox" name="mem_receive_email" id="mem_receive_email" value="1" <?= set_checkbox('mem_receive_email', '1', true); ?> /> 수신
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">SMS 문자 수신</label>
                <div class="col-md-9 text-left">
                    <div class="checkbox">
                        <label for="mem_receive_sms">
                            <input type="checkbox" name="mem_receive_sms" id="mem_receive_sms" value="1" <?= set_checkbox('mem_receive_sms', '1', true); ?> /> 수신
                        </label>
                    </div>
                </div>
            </div>
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
            <div class="form-group">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">회원가입</button>
                    <a href="<?= site_url(); ?>" class="btn btn-default">취소</a>
                </div>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url('static/js/member_register.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('static/js/captcha.js') ?>"></script>
<script type="text/javascript">
$(function() {
    $('#formRegister').validate({
        onkeyup: false,
        onclick: false,
        rules: {
            mem_userid: {required :true, minlength:3, maxlength:20, is_userid_available:true},
            mem_email: {required :true, email:true, is_email_available:true},
            mem_password: {required :true, is_password_available:true},
            mem_password_re : {required: true, equalTo : '#mem_password' },
            mem_nickname: {required :true, is_nickname_available:true},
            captcha_key : {required: true, captchaKey:true}
        },
        messages: {
            captcha_key: '자동등록방지용 코드가 올바르지 않습니다.'
        }
    });
});
</script>
<?= $this->endSection() ?>