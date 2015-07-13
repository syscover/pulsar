<style type="text/css">

    #loading-spinner {
        margin: -25px 0 0 -25px;
        width: 50px;
        height: 50px;
        position: absolute;
        left: 50%;
        top: 40%;
        z-index:1000001; /* makes sure it stays on top */
    }

    .alien {
        font-size: 90px;
        text-indent: -9999em;
        overflow: hidden;
        width: 1em;
        height: 1em;
        border-radius: 50%;
        position: relative;
        -webkit-animation: load6 1.7s infinite ease;
        animation: load6 1.7s infinite ease;
    }

    @-webkit-keyframes load6 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        5%, 95% {
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        30% {
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.51em -0.66em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.75em -0.36em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.83em -0.03em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.81em 0.21em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        55% {
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.29em -0.78em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.43em -0.72em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.52em -0.65em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.57em -0.61em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
    }
    @keyframes load6 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        5%, 95% {
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        30% {
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.51em -0.66em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.75em -0.36em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.83em -0.03em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.81em 0.21em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        55% {
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.29em -0.78em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.43em -0.72em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.52em -0.65em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.57em -0.61em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
            box-shadow: -0.11em -0.83em 0 -0.4em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.42em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.44em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.46em <?php echo $_POST['spinnerColor'] ?>, -0.11em -0.83em 0 -0.477em <?php echo $_POST['spinnerColor'] ?>;
        }
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="alien"></div>
</div>
<!-- /Preloader -->