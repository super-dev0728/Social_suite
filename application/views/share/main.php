<!doctype html>
<html lang="en" class="h-100">

<?php header('Access-Control-Allow-Origin: *'); ?>

<head>
    <meta charset="utf-8">
    <title>SHARE ON INSTAGRAM</title>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="//fonts.googleapis.com/css2?family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    body {
        background-color: #e9ecef;
    }

    .text-center {
        text-align: 'center';
    }

    .container {
        margin-top: 40px;
    }

    .clear {
        clear: both;
    }
    </style>
</head>

<body>

</body>
<div class="container text-center">
    <div class="logo">
        <a href="https://suite.social">
            <img height="80px" src="https://suite.social/images/favicon/icon_512px.png">
        </a>
        <p>Social Affiliates</p>
    </div>
    <div class="share-box">
        <div class="card card-outline card-danger">
            <div class="card-body">

                <!--============================================================= STEPS =============================================================-->

                <form class="testform" id="testform" method="#">
                    <fieldset>

                        <p></p>
                        <h3>1. Image Display</h3>
                        <p></p>
                        <hr><br>

                        <img src=<?php echo $img; ?>>

                    </fieldset>

                    <fieldset class="text-center">

                        <p></p>
                        <h3>2. Verify the link</h3>
                        <p></p>
                        <hr><br>

                        <div role="form">
                            <div class="form-group">
                                <label for="verify">Enter the image link to verify</label>
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="url" class="form-control image-link">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-verify">Click to
                                            Verify!</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-------------------- / FORM -------------------->

                    </fieldset>

                    <div class="clear"></div>

                </form>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
<script>
var url = "<?php echo site_url();?>";
</script>
<script src="<?php echo site_url('assets/js/jquery.min.js');?>"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</script>

<script>
function checkIfImageExists(url) {
    const img = new Image();

    img.src = url;

    if (img.complete) {
        alert('success!');
    } else {
        alert('failed!');
    }
}

$('.btn-verify').on('click', function() {
    if ($('.image-link').val() == '') {
        alert('Please enter the image url!');

        return;
    }
    var image_url = $('.image-link').val();

    checkIfImageExists(image_url);
});
</script>

</html>