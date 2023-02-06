const host = window.location.host + "/nemo"

function send_form(id,formId) {
    let request = new XMLHttpRequest();
    request.open("POST","https://" + host + "/process/process");

    request.onreadystatechange = function () {
        if(this.readyState === 4 && this.status === 200)
        {
            document.getElementById(id).innerHTML = request.responseText;

            let scripts = document.getElementById(id).getElementsByTagName("script");
            for (let i = 0; i < scripts.length; i++) {
                if (scripts[i].src !== "") {
                    let tag = document.createElement("script");
                    tag.src = scripts[i].src;
                    document.getElementsByTagName("head")[0].appendChild(tag);
                } else {
                    eval(scripts[i].innerHTML);
                }
            }

        } else if(this.readyState === 4 && this.status === 0) {//alert(id);
            notice("error","you are not connected");location.reload();
        }
        else {
            console.log(this.readyState + ' ' + this.status);
            console.log(this.response);
        }
    };

    let formStatus = document.getElementById(formId);
    let formStatusData = new FormData(formStatus);
    request.send(formStatusData);

    document.getElementById(id).innerHTML = '<i class="spinner-border spinner-border-sm text-green"></i>';
}

function send_data(id,str) {
    let hr = new XMLHttpRequest();
    let vars = str;
    hr.open("POST","https://" + host + "/process/process");
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    hr.onreadystatechange = function() {
        if(hr.readyState === 4 && hr.status === 200) {
            document.getElementById(id).innerHTML = hr.responseText;
            let scripts = document.getElementById(id).getElementsByTagName("script");
            for (let i = 0; i < scripts.length; i++) {
                if (scripts[i].src !== "") {
                    let tag = document.createElement("script");
                    tag.src = scripts[i].src;
                    document.getElementsByTagName("head")[0].appendChild(tag);
                } else {
                    eval(scripts[i].innerHTML);
                }
            }
        }else if(this.readyState === 4 && this.status === 0) {//alert(id);
            notice("error","you are not connected");location.reload();
        }
        else {
            console.log(this.readyState + ' ' + this.status);
            console.log(this.response);
        }
    }
    hr.send(vars);

    document.getElementById(id).innerHTML = '<i class="spinner-border spinner-border-sm text-green"></i>';

}

function notice(icon,text) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        confirmButtonText: 'Ok',
        timerProgressBar: false,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
    Toast.fire({
        icon: icon,
        text: text,
    });
}


/*      SPINNER     */
function clickMe(b) {
    var a = "<div id=\"temp\" class=\"spinner-border spinner-border-sm\"></div>" ;
    document.getElementById(b).innerHTML = a ;
}

function goto(line) {
    location.href = line;

}

// setInterval(() => notice('question','Are You Here'), 12000); // 4 minutes
setInterval(() => send_data('lastSeen','updateLastSeen'), 60000); // 5 minutes



/* start go to up */

window.onscroll = function () {
    if(window.scrollY >= 255) {
        if(document.querySelector('.goToUp') != null)
            document.querySelector('.goToUp').classList.add('showGoToUp');
    }
    else {
        if(document.querySelector('.goToUp') != null)
            document.querySelector('.goToUp').classList.remove('showGoToUp');
    }
}

function showGoToUpBtn() {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
}

/* end go to up */


/* start show login */
function showFormLogin() {
    setTimeout(() => {document.querySelector('.preloader').classList.add('hide')} ,2000);
    let login = document.querySelector('.login');
    login.classList.add('show');
}
/* end show login */


/* start for myMenu */

function editItemInfo(id,name,price,details,img= null) {
    let myItem = document.querySelector('.' + id).firstElementChild.lastElementChild.firstElementChild.firstElementChild
    if(img === "")
        img = "rings.jpg";
    if(img !== "dontChange")
        myItem.firstElementChild.firstElementChild.src = "https://" + host + "/img/menu/" + img;
    myItem.lastElementChild.firstElementChild.innerText = name;
    myItem.lastElementChild.children[1].innerText = price;
    myItem.lastElementChild.lastElementChild.firstElementChild.innerText = details;
}

function deleteItemFromCategory(categoryId,itemId) {
    document.querySelector("#table_" + categoryId ).removeChild(document.querySelector(".item_" + itemId ));
}

function addItemForCategory(categoryId,content) {
    document.querySelector("#table_" + categoryId ).innerHTML += content;
}
/* end for myMenu */



// (function f() {
// alert('1111');
// })();



































// let temp = "<div class=\"row\">\n" +
//     "    <div class=\"col-2 pt-lg-3 pt-md-3 text-center\">\n" +
//     "        <button type=\"button\" class=\"btn btn-outline-success btn-sm my-1\" onclick=\"send_data('spaceNotice','getModalEditItem&amp; itemId=9')\">\n" +
//     "            <i class=\"fa-solid fa-pen-to-square\"></i>\n" +
//     "        </button>\n" +
//     "        <button type=\"button\" class=\"btn btn-outline-danger btn-sm my-1\" onclick=\"send_data('spaceNotice','getModalDeleteItem&amp; itemId=9')\">\n" +
//     "            <i class=\"fa-solid fa-trash-can\"></i>\n" +
//     "        </button>\n" +
//     "    </div>\n" +
//     "    <div class=\"col-10\">\n" +
//     "        <div class=\"products-list product-list-in-card pl-2 pr-2 text-right\" dir=\"rtl\">\n" +
//     "            <div class=\"item\" id=\"9\">\n" +
//     "                <div class=\"product-img float-right\">\n" +
//     "                    <img src=\"https://127.0.0.1/nemo/img/menu/rings.jpg\" alt=\"item Image\" class=\"rounded\">\n" +
//     "                </div>\n" +
//     "                <div class=\"product-info ml-0 mr-5\">\n" +
//     "                    <lable class=\"product-title text-blue pr-2\">قهوة تركية\n" +
//     "                        <span class=\"badge badge-warning float-left\">2500 ل.س</span></lable>\n" +
//     "                    <br>\n" +
//     "                    <div class=\"container mr-3\">\n" +
//     "                        <span class=\"product-description pr-2\">ظرف شاي - مياه ساخنة - ظرف شاي</span>\n" +
//     "                    </div>\n" +
//     "                </div>\n" +
//     "            </div>\n" +
//     "        </div>\n" +
//     "    </div>\n" +
//     "</div>"
































































// let elem = document.querySelector('body')
// function openFullscreen() {
//     if (elem.requestFullscreen) {
//         elem.requestFullscreen();
//     } else if (elem.webkitRequestFullscreen) { /* Safari */
//         elem.webkitRequestFullscreen();
//     } else if (elem.msRequestFullscreen) { /* IE11 */
//         elem.msRequestFullscreen();
//     }
// }









/** login with facebook  **/
// window.fbAsyncInit = function() {
//     FB.init({
//         appId      : '2859803070993187',
//         cookie     : true,
//         xfbml      : true,
//         version    : 'v15.0'
//     });
//
//     FB.AppEvents.logPageView();
// };
//
// (function(d, s, id){
//     var js, fjs = d.getElementsByTagName(s)[0];
//     if (d.getElementById(id)) {return;}
//     js = d.createElement(s); js.id = id;
//     js.src = "https://connect.facebook.net/ar_AR/sdk.js";
//     fjs.parentNode.insertBefore(js, fjs);
// }(document, 'script', 'facebook-jssdk'));
//
// function loginWithFacebook() {
//     FB.login(function(response){
//         if(response.authResponse){
//             afterLogin();
//         }
//     });
// }
//
// function afterLogin() {
//     FB.getLoginStatus(function(response) {
//         if (response.status === 'connected'){
//             FB.api('/me', {fields: 'id,name,email,picture'}, (response) => {
//
//                 var id = response.id;
//                 var name = response.name;
//                 var email = response.email;
//                 var pic = response.picture.data.url;
//
//                 console.log(response);
//                 $.process({
//
//                     type: 'POST',
//                     url: 'https://byrings.rf.gd/ajax/loginFacebook',
//                     data: {id:id,name:name,email:email,pic:pic},
//                     success: function(data){
//
//                     }
//                 });
//             });
//         }
//
//     });
// }

