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

    /* Absolute Center CSS Spinner */
    .carousel {
        position: absolute;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }

    /* Transparent Overlay */
    .carousel:before {
        content: '';
        display: block;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* :not(:required) hides these rules from IE9 and below */
    .carousel:not(:required) {
        /* hide "loading..." text */
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
    }

    .carousel:not(:required):after {
        content: '';
        display: block;
        font-size: 10px;
        width: 1em;
        height: 1em;
        margin-top: -0.5em;
        -webkit-animation: spinner 1500ms infinite linear;
        -moz-animation: spinner 1500ms infinite linear;
        -ms-animation: spinner 1500ms infinite linear;
        -o-animation: spinner 1500ms infinite linear;
        animation: spinner 1500ms infinite linear;
        border-radius: 0.5em;
        -webkit-box-shadow: <?php echo $_POST['spinnerColor'] ?> 1.5em 0 0 0, <?php echo $_POST['spinnerColor'] ?> 1.1em 1.1em 0 0, <?php echo $_POST['spinnerColor'] ?> 0 1.5em 0 0, <?php echo $_POST['spinnerColor'] ?> -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, <?php echo $_POST['spinnerColor'] ?> 0 -1.5em 0 0, <?php echo $_POST['spinnerColor'] ?> 1.1em -1.1em 0 0;
        box-shadow: <?php echo $_POST['spinnerColor'] ?> 1.5em 0 0 0, <?php echo $_POST['spinnerColor'] ?> 1.1em 1.1em 0 0, <?php echo $_POST['spinnerColor'] ?> 0 1.5em 0 0, <?php echo $_POST['spinnerColor'] ?> -1.1em 1.1em 0 0, <?php echo $_POST['spinnerColor'] ?> -1.5em 0 0 0, <?php echo $_POST['spinnerColor'] ?> -1.1em -1.1em 0 0, <?php echo $_POST['spinnerColor'] ?> 0 -1.5em 0 0, <?php echo $_POST['spinnerColor'] ?> 1.1em -1.1em 0 0;
    }

    /* Animation */

    @-webkit-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @-moz-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @-o-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="carousel"></div>
</div>
<!-- /Preloader -->