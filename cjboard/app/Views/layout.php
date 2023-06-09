<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Starter Template for Bootstrap</title>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="<?= base_url('static/lib/bootstrap/assets/js/vendor/jquery.min.js') ?>"><\/script>')</script>
    <script src="<?= base_url('static/lib/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?= base_url('static/lib/bootstrap/assets/js/ie10-viewport-bug-workaround.js') ?>"></script>
    <!-- Bootstrap core CSS -->
    <link href="<?= base_url('static/lib/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css" />
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?= base_url('static/lib/bootstrap/assets/css/ie10-viewport-bug-workaround.css') ?>" rel="stylesheet">
    <link href="<?= base_url('static/css/nomalize.css') ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= base_url('static/css/common.css') ?>" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?= base_url('static/lib/bootstrap/assets/js/ie8-responsive-file-warning.js') ?>"></script><![endif]-->
    <script src="<?= base_url('static/lib/bootstrap/assets/js/ie-emulation-modes-warning.js') ?>"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="<?= base_url('static/js/common.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('static/lib/jquery/jquery.validate.min.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('static/lib/jquery/jquery.validate.extension.js') ?>"></script>
    <script type="text/javascript">
        // 자바스크립트에서 사용하는 전역변수 선언
        var cb_url = "<?= trim(site_url(), '/'); ?>";
        var cb_csrf_hash = "<?= csrf_hash() ?>";

    </script>
</head>
<body>
<?= $this->include('header') ?>
<div class="container-fluid content">
    <?= $this->renderSection('content') ?>
</div>
<?= $this->include('footer') ?>

</body>
</html>
