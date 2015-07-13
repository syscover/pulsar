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

    @-webkit-keyframes three-quarters {
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

    @-moz-keyframes three-quarters {
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

    @-o-keyframes three-quarters {
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

    @keyframes three-quarters {
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
    .three-quarters {
        font-family: sans-serif;
        font-weight: 100;
    }

    /* :not(:required) hides this rule from IE9 and below */
    .three-quarters:not(:required) {
        -webkit-animation: three-quarters 1250ms infinite linear;
        -moz-animation: three-quarters 1250ms infinite linear;
        -ms-animation: three-quarters 1250ms infinite linear;
        -o-animation: three-quarters 1250ms infinite linear;
        animation: three-quarters 1250ms infinite linear;
        border: 8px solid <?php echo $_POST['spinnerColor'] ?>;
        border-right-color: transparent;
        border-radius: 16px;
        box-sizing: border-box;
        display: inline-block;
        position: relative;
        overflow: hidden;
        text-indent: -9999px;
        width: 32px;
        height: 32px;
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="three-quarters"></div>
</div>
<!-- /Preloader -->