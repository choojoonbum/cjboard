<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="access col-md-6 col-md-offset-3">
    <div class="panel panel-primary">
        <div class="panel-heading">로그인</div>
        <div class="panel-body">
            <?php
            $attributes = array('class' => 'form-horizontal', 'name' => 'frmLogin', 'id' => 'frmLogin');
            echo form_open('', $attributes);
            if (empty($errors)) $errors = [];
            ?>
            <input type="hidden" name="url" value="<?php echo esc(''); ?>" />
            <div class="form-group <?php if (val('mem_userid', $errors)) { ?>has-error<?php } ?>">
                <label class="col-lg-3 control-label">아이디</label>
                <div class="col-lg-9">
                    <input type="text" name="mem_userid" class="form-control" value="<?php echo set_value('mem_userid'); ?>" accesskey="L" />
                    <?php if (val('mem_userid', $errors)) { ?>
                        <div class="text-danger text-left"><?= val('mem_userid', $errors) ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group <?php if (val('mem_password', $errors)) { ?>has-error<?php } ?>">
                <label class="col-lg-3 control-label">비밀번호</label>
                <div class="col-lg-9">
                    <input type="password" class="form-control" name="mem_password" />
                    <?php if (val('mem_password', $errors)) { ?>
                        <div class="text-danger text-left"><?= val('mem_password', $errors) ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-8 text-right">
                    <button type="submit" class="btn btn-primary btn-sm">로그인</button>
                </div>
                <div class="col-lg-4">
                    <label for="autologin">
                        <input type="checkbox" name="autologin" id="autologin" value="1" /> 자동로그인
                    </label>
                </div>
            </div>
            <div class="alert alert-dismissible alert-info autologinalert" style="display:none;">
                자동로그인 기능을 사용하시면, 브라우저를 닫더라도 로그인이 계속 유지될 수 있습니다. 자동로그이 기능을 사용할 경우 다음 접속부터는 로그인할 필요가 없습니다. 단, 공공장소에서 이용 시 개인정보가 유출될 수 있으니 꼭 로그아웃을 해주세요.
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="panel-footer text-right">
            <a href="<?php echo site_url('member/agreement'); ?>" class="btn btn-success btn-sm" title="회원가입">회원가입</a>
            <a href="<?php echo site_url('member/find'); ?>" class="btn btn-default btn-sm" title="아이디 패스워드 찾기">아이디 패스워드 찾기</a>
        </div>
    </div>
</div>
</div>
<?= $this->endSection() ?>
