$(window).ready(function () {
    //theme management
    if (
        $("html").data("theme-fluid") &&
        window.matchMedia &&
        window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
        if (document.cookie.indexOf("theme-color") == -1) {
            switch_theme("dark");
        }
    }

    $(".theme-switch .icon-box").click(function () {
        const theme = $(this).data("theme");

        switch_theme(theme);
    });

    function switch_theme(theme) {
        let expiration = new Date();

        $("html").addClass("color-scheme-transition");

        $("html").attr("data-theme", theme);

        setTimeout(function () {
            $("html").removeClass("color-scheme-transition");
        }, 500);

        expiration.setMonth(expiration.getMonth() + 1);

        document.cookie =
            "theme-color=" + theme + "; expires= " + expiration + "; path=/";
    }
});
