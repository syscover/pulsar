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

    .sonar {
        width: 40px;
        height: 40px;
        background-color: <?php echo $_POST['spinnerColor'] ?>;
        border-radius: 100%;
        -webkit-animation: scaleout 1.0s infinite ease-in-out;
        animation: scaleout 1.0s infinite ease-in-out;
    }

    @-webkit-keyframes scaleout {
        0% { -webkit-transform: scale(0.0) }
        100% {
            -webkit-transform: scale(1.0);
            opacity: 0;
        }
    }

    @keyframes scaleout {
        0% {
            transform: scale(0.0);
            -webkit-transform: scale(0.0);
        } 100% {
              transform: scale(1.0);
              -webkit-transform: scale(1.0);
              opacity: 0;
          }
    }
</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="sonar"></div>
</div>
<!-- /Preloader -->