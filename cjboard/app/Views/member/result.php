<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">회원가입을 축하합니다.</div>
            <div class="panel-body">
                <span class="text-primary"><?= esc(session()->get('nickname')); ?></span>님의 회원가입을 진심으로 축하드립니다. <br />
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>