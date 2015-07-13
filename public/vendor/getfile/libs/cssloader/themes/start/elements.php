<style type="text/css">

#loading-spinner {
    margin: -25px 0 0 -25px;
    width: 50px;
    height: 50px;
    position: absolute;
    left: 50%;
    top: 40%;
    z-index: 1000001; /* makes sure it stays on top */
}

.start {
    height:100px;
    position:relative;
    width:80px;
}
.start > div {
    background-color:#FFFFFF;
    height:30px;
    position:absolute;
    width:12px;

    /* css3 radius */
    -moz-border-radius:5px;
    -webkit-border-radius:5px;
    border-radius:5px;

    /* css3 transform - scale */
    -webkit-transform:scale(0.4);
    -moz-transform:scale(0.4);
    -o-transform:scale(0.4);

    /* css3 animation */
    -webkit-animation-name:start;
    -webkit-animation-duration:1.04s;
    -webkit-animation-iteration-count:infinite;
    -webkit-animation-direction:linear;
    -moz-animation-name:start;
    -moz-animation-duration:1.04s;
    -moz-animation-iteration-count:infinite;
    -moz-animation-direction:linear;
    -o-animation-name:start;
    -o-animation-duration:1.04s;
    -o-animation-iteration-count:infinite;
    -o-animation-direction:linear;
}
.start > div:nth-child(1) {
    left:0;
    top:36px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(-90deg);
    -moz-transform:rotate(-90deg);
    -o-transform:rotate(-90deg);

    /* css3 animation */
    -webkit-animation-delay:0.39s;
    -moz-animation-delay:0.39s;
    -o-animation-delay:0.39s;
}

.start > div:nth-child(2) {
    left:10px;
    top:13px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(-45deg);
    -moz-transform:rotate(-45deg);
    -o-transform:rotate(-45deg);

    /* css3 animation */
    -webkit-animation-delay:0.52s;
    -moz-animation-delay:0.52s;
    -o-animation-delay:0.52s;
}

.start > div:nth-child(3) {
    left:34px;
    top:4px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(0deg);
    -moz-transform:rotate(0deg);
    -o-transform:rotate(0deg);

    /* css3 animation */
    -webkit-animation-delay:0.65s;
    -moz-animation-delay:0.65s;
    -o-animation-delay:0.65s;
}

.start > div:nth-child(4) {
    right:10px;
    top:13px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(45deg);
    -moz-transform:rotate(45deg);
    -o-transform:rotate(45deg);

    /* css3 animation */
    -webkit-animation-delay:0.78s;
    -moz-animation-delay:0.78s;
    -o-animation-delay:0.78s;
}
.start > div:nth-child(5) {
    right:0;
    top:36px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(90deg);
    -moz-transform:rotate(90deg);
    -o-transform:rotate(90deg);

    /* css3 animation */
    -webkit-animation-delay:0.91s;
    -moz-animation-delay:0.91s;
    -o-animation-delay:0.91s;
}
.start > div:nth-child(6) {
    right:10px;
    bottom:9px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(135deg);
    -moz-transform:rotate(135deg);
    -o-transform:rotate(135deg);

    /* css3 animation */
    -webkit-animation-delay:1.04s;
    -moz-animation-delay:1.04s;
    -o-animation-delay:1.04s;
}
.start > div:nth-child(7) {
    bottom:0;
    left:34px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(180deg);
    -moz-transform:rotate(180deg);
    -o-transform:rotate(180deg);

    /* css3 animation */
    -webkit-animation-delay:1.17s;
    -moz-animation-delay:1.17s;
    -o-animation-delay:1.17s;
}
.start > div:nth-child(8) {
    left:10px;
    bottom:9px;

    /* css3 transform - rotate */
    -webkit-transform:rotate(-135deg);
    -moz-transform:rotate(-135deg);
    -o-transform:rotate(-135deg);

    /* css3 animation */
    -webkit-animation-delay:1.3s;
    -moz-animation-delay:1.3s;
    -o-animation-delay:1.3s;
}

/* css3 keyframes - start */
@-webkit-keyframes start {
    0%{ background-color:<?php echo $_POST['spinnerColor'] ?> }
    100%{ background-color:#FFFFFF }
}
@-moz-keyframes start {
    0%{ background-color:<?php echo $_POST['spinnerColor'] ?> }
    100%{ background-color:#FFFFFF }
}
@-o-keyframes start {
    0%{ background-color:<?php echo $_POST['spinnerColor'] ?> }
    100%{ background-color:#FFFFFF }
}


</style>
<!-- Preloader -->
<div id="loading-spinner">
    <div class="start">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- /Preloader -->