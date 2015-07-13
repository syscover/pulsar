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

    @-webkit-keyframes timer {
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

    @-moz-keyframes timer {
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

    @-o-keyframes timer {
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

    @keyframes timer {
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

    /* Styles for old versions of IE */
    .timer {
        font-family: sans-serif;
        font-weight: 100;
    }

    /* :not(:required) hides this rule from IE9 and below */
    .timer:not(:required) {
        border: 6px solid <?php echo $_POST['spinnerColor'] ?>;
        -webkit-border-radius: 24px;
        -moz-border-radius: 24px;
        -ms-border-radius: 24px;
        -o-border-radius: 24px;
        border-radius: 24px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        display: inline-block;
        overflow: hidden;
        position: relative;
        text-indent: -9999px;
        width: 48px;
        height: 48px;
    }
    .timer:not(:required)::before {
        -webkit-animation: timer 1250ms infinite linear;
        -moz-animation: timer 1250ms infinite linear;
        -ms-animation: timer 1250ms infinite linear;
        -o-animation: timer 1250ms infinite linear;
        animation: timer 1250ms infinite linear;
        -webkit-transform-origin: 3px 3px;
        -moz-transform-origin: 3px 3px;
        -ms-transform-origin: 3px 3px;
        -o-transform-origin: 3px 3px;
        transform-origin: 3px 3px;
        background: <?php echo $_POST['spinnerColor'] ?>;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        -o-border-radius: 3px;
        border-radius: 3px;
        content: '';
        display: block;
        position: absolute;
        width: 6px;
        height: 19.2px;
        left: 15px;
        top: 15px;
    }
    .timer:not(:required)::after {
        -webkit-animation: timer 15000ms infinite linear;
        -moz-animation: timer 15000ms infinite linear;
        -ms-animation: timer 15000ms infinite linear;
        -o-animation: timer 15000ms infinite linear;
        animation: timer 15000ms infinite linear;
        -webkit-transform-origin: 3px 3px;
        -moz-transform-origin: 3px 3px;
        -ms-transform-origin: 3px 3px;
        -o-transform-origin: 3px 3px;
        transform-origin: 3px 3px;
        background: <?php echo $_POST['spinnerColor'] ?>;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        -o-border-radius: 3px;
        border-radius: 3px;
        content: '';
        display: block;
        position: absolute;
        width: 6px;
        height: 16px;
        left: 15px;
        top: 15px;
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="timer"></div>
</div>
<!-- /Preloader -->