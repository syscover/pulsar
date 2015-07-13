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

    @-webkit-keyframes ball {
        0% {
            -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -o-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }
    }

    @-moz-keyframes ball {
        0% {
            -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -o-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }
    }

    @-o-keyframes ball {
        0% {
            -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -o-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }
    }

    @keyframes ball {
        0% {
            -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -o-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.95, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 0.5, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -moz-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -ms-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -o-transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            transform: translate3d(0, 150px, -10px) scale3d(1, 1.25, 1);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }
    }

    @-webkit-keyframes ball-highlight {
        0% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @-moz-keyframes ball-highlight {
        0% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @-o-keyframes ball-highlight {
        0% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @keyframes ball-highlight {
        0% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -moz-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -ms-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -o-transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            transform: skew(-30deg, 0) translate3d(0, 0, 1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @-webkit-keyframes ball-shadow {
        0% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -moz-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -ms-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -o-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @-moz-keyframes ball-shadow {
        0% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -moz-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -ms-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -o-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @-o-keyframes ball-shadow {
        0% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -moz-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -ms-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -o-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    @keyframes ball-shadow {
        0% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        45% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }

        50% {
            -webkit-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -moz-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -ms-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -o-transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            transform: translate3d(12.5px, -15px, -1px) scale3d(1, 1, 1);
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -ms-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
        }

        55% {
            -webkit-transform: translate3d(12.5px, -15px, -1px);
            -moz-transform: translate3d(12.5px, -15px, -1px);
            -ms-transform: translate3d(12.5px, -15px, -1px);
            -o-transform: translate3d(12.5px, -15px, -1px);
            transform: translate3d(12.5px, -15px, -1px);
            -webkit-animation-timing-function: ease-out;
            -moz-animation-timing-function: ease-out;
            -ms-animation-timing-function: ease-out;
            -o-animation-timing-function: ease-out;
            animation-timing-function: ease-out;
        }

        100% {
            -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
            -webkit-animation-timing-function: ease-in;
            -moz-animation-timing-function: ease-in;
            -ms-animation-timing-function: ease-in;
            -o-animation-timing-function: ease-in;
            animation-timing-function: ease-in;
        }
    }

    /* Styles for old versions of IE */
    .ball {
        font-family: sans-serif;
        font-weight: 100;
    }

    /* :not(:required) hides this rule from IE9 and below */
    .ball:not(:required) {
        position: relative;
        display: inline-block;
        font-size: 0;
        letter-spacing: -1px;
        border-radius: 100%;
        background: #ff8866;
        width: 50px;
        height: 50px;
        -webkit-transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        -ms-transform-style: preserve-3d;
        -o-transform-style: preserve-3d;
        transform-style: preserve-3d;
        -webkit-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
        -moz-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
        -ms-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
        -o-transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
        transform: translate3d(0, 0, 0) scale3d(1, 1, 1);
        -webkit-transform-origin: 0 100%;
        -moz-transform-origin: 0 100%;
        -ms-transform-origin: 0 100%;
        -o-transform-origin: 0 100%;
        transform-origin: 0 100%;
        -webkit-animation: ball 1500ms infinite linear;
        -moz-animation: ball 1500ms infinite linear;
        -ms-animation: ball 1500ms infinite linear;
        -o-animation: ball 1500ms infinite linear;
        animation: ball 1500ms infinite linear;
    }
    .ball:not(:required)::after {
        content: '';
        position: absolute;
        top: 4.5px;
        left: 5.5px;
        width: 15px;
        height: 15px;
        background: #ffb099;
        border-radius: 100%;
        -webkit-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
        -moz-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
        -ms-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
        -o-transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
        transform: skew(-20deg, 0) translate3d(0, 2.5px, 1px);
        -webkit-animation: ball-highlight 1500ms infinite linear;
        -moz-animation: ball-highlight 1500ms infinite linear;
        -ms-animation: ball-highlight 1500ms infinite linear;
        -o-animation: ball-highlight 1500ms infinite linear;
        animation: ball-highlight 1500ms infinite linear;
    }
    .ball:not(:required)::before {
        content: '';
        position: absolute;
        top: 50px;
        left: 5.5px;
        width: 50px;
        height: 15px;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 100%;
        -webkit-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
        -moz-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
        -ms-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
        -o-transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
        transform: translate3d(66.66667px, 66.66667px, -1px) scale3d(1.25, 1.25, 1);
        -webkit-animation: ball-shadow 1500ms infinite linear;
        -moz-animation: ball-shadow 1500ms infinite linear;
        -ms-animation: ball-shadow 1500ms infinite linear;
        -o-animation: ball-shadow 1500ms infinite linear;
        animation: ball-shadow 1500ms infinite linear;
        -webkit-filter: blur(1px);
        -moz-filter: blur(1px);
        filter: blur(1px);
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="ball"></div>
</div>
<!-- /Preloader -->