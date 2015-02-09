<style type="text/css">
    #preloader {
        position: fixed;
        top:0;
        left:0;
        right:0;
        bottom:0;
        background-color:#fff; /* change if the mask should have another color then white */
        z-index:98; /* makes sure it stays on top */
    }

    #loading-spinner {
        margin: -25px 0 0 -25px;
        width: 50px;
        height: 50px;
        position: absolute;
        left: 50%;
        top: 40%;
        z-index:99; /* makes sure it stays on top */
    }

    .spinner {
        width: 50px;
        height: 30px;
        text-align: center;
        font-size: 10px;
    }

    .spinner > div {
        background-color: <?php echo $_POST['spinnerColor'] ?>;
        height: 100%;
        width: 6px;
        display: inline-block;
        -webkit-animation: stretchdelay 1.2s infinite ease-in-out;
        animation: stretchdelay 1.2s infinite ease-in-out;
    }

    .spinner .rect2 {
        -webkit-animation-delay: -1.1s;
        animation-delay: -1.1s;
    }

    .spinner .rect3 {
        -webkit-animation-delay: -1.0s;
        animation-delay: -1.0s;
    }

    .spinner .rect4 {
        -webkit-animation-delay: -0.9s;
        animation-delay: -0.9s;
    }

    .spinner .rect5 {
        -webkit-animation-delay: -0.8s;
        animation-delay: -0.8s;
    }

    @-webkit-keyframes stretchdelay {
        0%, 40%, 100% { -webkit-transform: scaleY(0.4) }
        20% { -webkit-transform: scaleY(1.0) }
    }

    @keyframes stretchdelay {
        0%, 40%, 100% {
            transform: scaleY(0.4);
            -webkit-transform: scaleY(0.4);
        }  20% {
               transform: scaleY(1.0);
               -webkit-transform: scaleY(1.0);
           }
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<?php if ($_POST['useLayer'] == "true"): ?>
<div id="preloader"></div>
<?php endif; ?>
<!-- /Preloader -->