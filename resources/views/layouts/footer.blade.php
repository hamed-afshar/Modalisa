<style>
    .footer-text {
        font-family: Roboto;
        font-size: medium;
        text-align: justify;
    }

    .logo-text {
        font-size: x-large;
        font-family: Roboto;
    }
</style>
<footer class="pt-4 mt-5 bg-dark text-white" style="height: auto">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 footer-text mt-5">
                <div class="pl-3"> {{ __("translate.home") }} </div>
                <div class="pl-3"> {{ __("translate.about") }} </div>
                <div class="pl-3"> {{ __("translate.contact") }} </div>
                <div class="pl-3"> {{ __("translate.faq") }} </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="logo-text">
                    <h1 class="text-center">Modalisa</h1>
                </div>
                <div class="text-center">
                    <i class="fab fa-instagram mr-2"></i>
                    <i class="fab fa-whatsapp mr-2"></i>
                    <i class="fab fa-telegram-plane mr-2"></i>
                    <i class="fas fa-envelope-open mr-2"></i>
                </div>
                <div class="text-center mt-1">
                    <small class="text-white pr-2" style="border-right: 1px  solid #ffffff"> All Rights Reserved</small>
                    <small class="text-white pl-2"> Powered By Modalisa</small>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 footer-text">
                <div class="pl-3">
                    <p> {{ __("translate.footer_text") }}
                        <button type="button" class="btn btn-light btn-block">Register</button>
                    </p>

                </div>

            </div>
        </div>
    </div>
</footer>


