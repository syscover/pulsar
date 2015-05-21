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
        margin: 100px auto;
        width: 40px;
        height: 40px;
        position: relative;
        text-align: center;

        -webkit-animation: rotate 2.0s infinite linear;
        animation: rotate 2.0s infinite linear;
    }

    .dot1, .dot2 {
        width: 60%;
        height: 60%;
        display: inline-block;
        position: absolute;
        top: 0;
        background-color: <?php echo $_POST['spinnerColor'] ?>;
        border-radius: 100%;
        -webkit-animation: bounce 2.0s infinite ease-in-out;
        animation: bounce 2.0s infinite ease-in-out;
    }

    .dot2 {
        top: auto;
        bottom: 0px;
        -webkit-animation-delay: -1.0s;
        animation-delay: -1.0s;
    }

    @-webkit-keyframes rotate { 100% { -webkit-transform: rotate(360deg) }}
    @keyframes rotate { 100% { transform: rotate(360deg); -webkit-transform: rotate(360deg) }}

    @-webkit-keyframes bounce {
        0%, 100% { -webkit-transform: scale(0.0) }
        50% { -webkit-transform: scale(1.0) }
    }

    @keyframes bounce {
        0%, 100% {
            transform: scale(0.0);
            -webkit-transform: scale(0.0);
        } 50% {
              transform: scale(1.0);
              -webkit-transform: scale(1.0);
          }
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="spinner">
        <div class="dot1"></div>
        <div class="dot2"></div>
    </div>
</div>
<?php if ($_POST['useLayer'] == "true"): ?>
<div id="preloader"></div>
<?php endif; ?>
<!-- /Preloader -->