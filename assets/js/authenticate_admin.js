var $ = jQuery;

class authenticateAdmin {
    constructor() {
        this.events();
        this.ajaxAuthentication();
    }

    events() {
        $(".loginform").submit(this.createCookie);
        $(".end-the-day").on('click', this.openPageModal);
        $(".do-not-end-day").on('click', this.closeModal);
    }

    createCookie() {
        document.cookie = `username=${$("input[name=username]").val()}`;
        document.cookie = `password=${$("input[name=passwd]").val()}`;
    }

    getCookie(cookieName) {
        var cookieName = cookieName + "=";
        var decodeCookieForSpecialChars = decodeURIComponent(document.cookie);
        var cookieToArray = decodeCookieForSpecialChars.split(';');


        for(var i=0; i<cookieToArray.length; i++) {
            var theCookie = cookieToArray[i];
            while(theCookie.charAt(0) == ' ') {
                theCookie = theCookie.substring(1)
            }
            if(theCookie.indexOf(cookieName) == 0) {
                return theCookie.substring(cookieName.length, theCookie.length);
            }
        }
    }

    openPageModal() {
        $("#end-the-day-notification").css({
            'z-index': '999999999',
            'opacity': 1
        })

        setTimeout(function() {
            $("#notification-wrapper").css({
                'top': 0,
                'opacity': 1
            })
        }, 200)
    }

    closeModal() {
        setTimeout(function() {
            $("#end-the-day-notification").css({
                'z-index': '-9999999999999999999999999999999999999',
                'opacity': 0
            });
        }, 200)

        $("#notification-wrapper").css({
            'top': -100,
            'opacity': 0
        })
    }

    ajaxAuthentication() {
        const credentials = {
            username: this.getCookie("username"),
            password: this.getCookie("password")
        }

        setInterval(() => {
            //console.log(credentials);
            $.ajax({
                url: `authenticate.php`,
                type: 'POST',
                data: credentials,
            })
        }, 2000);


    }
}

new authenticateAdmin;