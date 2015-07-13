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

    @-webkit-keyframes dots {
        0% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        8.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        16.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        25% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        33.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
        }

        41.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        50% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        58.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        66.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        75% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        83.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
        }

        91.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        100% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }
    }

    @-moz-keyframes dots {
        0% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        8.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        16.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        25% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        33.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
        }

        41.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        50% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        58.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        66.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        75% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        83.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
        }

        91.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        100% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }
    }

    @-o-keyframes dots {
        0% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        8.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        16.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        25% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        33.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
        }

        41.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        50% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        58.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        66.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        75% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        83.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
        }

        91.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        100% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }
    }

    @keyframes dots {
        0% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        8.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        16.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px 14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        25% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        33.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee -14px -14px 0 7px;
        }

        41.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        50% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        58.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 -14px 14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        66.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 -14px -14px 0 7px, #66dd77 -14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        75% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px -14px 0 7px, #44aaee 14px -14px 0 7px;
        }

        83.33% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee 14px 14px 0 7px;
        }

        91.67% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px 14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }

        100% {
            -webkit-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            -moz-box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
            box-shadow: white 0 0 15px 0, #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        }
    }

    /* Styles for old versions of IE */
    .dots {
        font-family: sans-serif;
        font-weight: 100;
    }

    /* :not(:required) hides this rule from IE9 and below */
    .dots:not(:required) {
        overflow: hidden;
        position: relative;
        text-indent: -9999px;
        display: inline-block;
        width: 7px;
        height: 7px;
        background: transparent;
        border-radius: 100%;
        -webkit-box-shadow: #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        -moz-box-shadow: #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        box-shadow: #ff8866 -14px -14px 0 7px, #ffcc66 14px -14px 0 7px, #66dd77 14px 14px 0 7px, #44aaee -14px 14px 0 7px;
        -webkit-animation: dots 5s infinite ease-in-out;
        -moz-animation: dots 5s infinite ease-in-out;
        -ms-animation: dots 5s infinite ease-in-out;
        -o-animation: dots 5s infinite ease-in-out;
        animation: dots 5s infinite ease-in-out;
        -webkit-transform-origin: 50% 50%;
        -moz-transform-origin: 50% 50%;
        -ms-transform-origin: 50% 50%;
        -o-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="dots"></div>
</div>
<!-- /Preloader -->