<style>
    .spinner {
        margin: 100px auto;
        width: 40px;
        height: 40px;
        position: absolute;
    }

    .cube1,
    .cube2 {
        background-color: #333;
        width: 15px;
        height: 15px;
        position: absolute;
        top: 0;
        left: 0;

        -webkit-animation: sk-cubemove 1.8s infinite ease-in-out;
        animation: sk-cubemove 1.8s infinite ease-in-out;
    }

    .cube2 {
        -webkit-animation-delay: -0.9s;
        animation-delay: -0.9s;
    }

    @-webkit-keyframes sk-cubemove {
        25% {
            -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5)
        }

        50% {
            -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg)
        }

        75% {
            -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5)
        }

        100% {
            -webkit-transform: rotate(-360deg)
        }
    }

    @keyframes sk-cubemove {
        25% {
            transform: translateX(42px) rotate(-90deg) scale(0.5);
            -webkit-transform: translateX(42px) rotate(-90deg) scale(0.5);
        }

        50% {
            transform: translateX(42px) translateY(42px) rotate(-179deg);
            -webkit-transform: translateX(42px) translateY(42px) rotate(-179deg);
        }

        50.1% {
            transform: translateX(42px) translateY(42px) rotate(-180deg);
            -webkit-transform: translateX(42px) translateY(42px) rotate(-180deg);
        }

        75% {
            transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
            -webkit-transform: translateX(0px) translateY(42px) rotate(-270deg) scale(0.5);
        }

        100% {
            transform: rotate(-360deg);
            -webkit-transform: rotate(-360deg);
        }
    }

    .bd-spinner-modal-lg .modal-dialog {
        display: table;
        position: relative;
        margin: 0 auto;
        top: calc(50% - 24px);
        z-index: 9999;
        /* Sit on top - higher than any other z-index in your site*/
    }

    .bd-spinner-modal-lg .modal-dialog .modal-content {
        background-color: transparent;
        border: none;
        z-index: 9998;
        /* Sit on top - higher than any other z-index in your site*/
    }
</style>


<div class=" fade bd-spinner-modal-lg" id="modal-spinner" data-backdrop="static" data-keyboard="false" aria-hidden="true" tabindex="999">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="width: 50px">
            <div class="spinner">
                <div class="cube1"></div>
                <div class="cube2"></div>
            </div>
        </div>
    </div>
</div>
