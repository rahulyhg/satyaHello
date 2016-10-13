function getCookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
            return unescape(y);
        }
    }
}

//function setCookie(c_name, value, exdays) {
//    var exdate = new Date();
//    exdate.setDate(exdate.getDate() + exdays);
//    var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
//    document.cookie = c_name + "=" + c_value;
//}


function eraseCookie(name) {
    setCookie("UserName", "", -1);
//    setCookie("UserId", "", -1);
//    setCookie("KidImg", "", -1);
//    setCookie("ThumbnailKidImg", "", -1);
}


function setCookie(name, value, expires) {
    var cookie = name + "=" + escape(value) + ";";

    var path = "/";

    var domain ="http://hello42";
    if (expires) {
        // If it's a date
        if (expires instanceof Date) {
            // If it isn't a valid date
            if (isNaN(expires.getTime()))
                expires = new Date();
        }
        else
            expires = new Date(new Date().getTime() + parseInt(expires) * 1000 * 60 * 60 * 24);

        cookie += "expires=" + expires.toGMTString() + ";";
    }

    if (path)
        cookie += "path=" + path + ";";
    if (domain)
        cookie += "domain=" + domain + ";";

    document.cookie = cookie;
}

function deleteCookie(name, path, domain) {
    // If the cookie exists
    if (getCookie(name))
        createCookie(name, "", -1, path, domain);
}


//function getCookie(name) {
//    var regexp = new RegExp("(?:^" + name + "|;\s*" + name + ")=(.*?)(?:;|$)", "g");
//    var regexp = regexp + "path=" + path + ";" + "domain=" + domain + ";";
//    var result = regexp.exec(document.cookie);
//    return (result === null) ? null : result[1];
//}