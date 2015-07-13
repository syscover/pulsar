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

    /* Styles for old versions of IE */
    .heartbeat {
        font-family: sans-serif;
        font-weight: 100;
    }

    @-webkit-keyframes heartbeat {
        0% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        14% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        28% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        42% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        70% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }
    }

    @-moz-keyframes heartbeat {
        0% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        14% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        28% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        42% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        70% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }
    }

    @-o-keyframes heartbeat {
        0% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        14% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        28% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        42% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        70% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }
    }

    @keyframes heartbeat {
        0% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        14% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        28% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }

        42% {
            -webkit-transform: rotate(45deg) scale(1.3);
            -moz-transform: rotate(45deg) scale(1.3);
            -ms-transform: rotate(45deg) scale(1.3);
            -o-transform: rotate(45deg) scale(1.3);
            transform: rotate(45deg) scale(1.3);
        }

        70% {
            -webkit-transform: rotate(45deg) scale(1);
            -moz-transform: rotate(45deg) scale(1);
            -ms-transform: rotate(45deg) scale(1);
            -o-transform: rotate(45deg) scale(1);
            transform: rotate(45deg) scale(1);
        }
    }

    /* :not(:required) hides this rule from IE9 and below */
    .heartbeat:not(:required) {
        -webkit-animation: heartbeat 1300ms ease 0s infinite normal;
        -moz-animation: heartbeat 1300ms ease 0s infinite normal;
        -ms-animation: heartbeat 1300ms ease 0s infinite normal;
        -o-animation: heartbeat 1300ms ease 0s infinite normal;
        animation: heartbeat 1300ms ease 0s infinite normal;
        display: inline-block;
        position: relative;
        overflow: hidden;
        text-indent: -9999px;
        width: 36px;
        height: 36px;
        -webkit-transform: rotate(45deg) scale(1);
        -moz-transform: rotate(45deg) scale(1);
        -ms-transform: rotate(45deg) scale(1);
        -o-transform: rotate(45deg) scale(1);
        transform: rotate(45deg) scale(1);
        -webkit-transform-origin: 50% 50%;
        -moz-transform-origin: 50% 50%;
        -ms-transform-origin: 50% 50%;
        -o-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
    }
    .heartbeat:not(:required):after, .heartbeat:not(:required):before {
        position: absolute;
        content: "";
        background: <?php echo $_POST['spinnerColor'] ?>;
    }
    .heartbeat:not(:required):before {
        -moz-border-radius-topleft: 12px;
        -webkit-border-top-left-radius: 12px;
        border-top-left-radius: 12px;
        -moz-border-radius-bottomleft: 12px;
        -webkit-border-bottom-left-radius: 12px;
        border-bottom-left-radius: 12px;
        top: 12px;
        left: 0;
        width: 36px;
        height: 24px;
    }
    .heartbeat:not(:required):after {
        -moz-border-radius-topleft: 12px;
        -webkit-border-top-left-radius: 12px;
        border-top-left-radius: 12px;
        -moz-border-radius-topright: 12px;
        -webkit-border-top-right-radius: 12px;
        border-top-right-radius: 12px;
        top: 0;
        left: 12px;
        width: 24px;
        height: 12px;
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="heartbeat"></div>
</div>
<!-- /Preloader -->