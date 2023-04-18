<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <?php
    $attributes = array('class' => 'form-horizontal', 'name' => 'fregisterForm', 'id' => 'fregisterForm');
    echo form_open('', $attributes);
    echo btAlert(session()->getFlashdata('message'));
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">회원가입</div>
        <div class="panel-body">
            <p><strong>회원가입약관</strong></p>
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea class="form-control" rows="3" readonly="readonly"><?= esc(val('member_register_policy1', $view)) ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <div class="checkbox">
                        <label for="agree">
                            <input type="checkbox" name="agree" id="agree" value="1" /> 회원가입약관의 내용에 동의합니다.
                        </label>
                    </div>
                </div>
            </div>
            <p><strong>개인정보취급방침안내</strong></p>
            <div class="form-group">
                <div class="col-lg-12">
                    <textarea class="form-control" rows="3" readonly="readonly"><?= esc(val('member_register_policy2', $view)) ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <div class="checkbox">
                        <label for="agree2">
                            <input type="checkbox" name="agree2" id="agree2" value="1" /> 개인정보취급방침안내의 내용에 동의합니다.
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary">회원가입</button>
                </div>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
    //<![CDATA[
    $(function() {
        $('#fregisterForm').validate({
            rules: {
                agree: {required :true},
                agree2: {required :true}
            }
        });
    });
    //]]>
</script>
<?= $this->endSection() ?>

