var keys=[];
var values=[];
$(document).ready(function(){
    $(document).on("click","button",function(){    //handle add to cart button
        //var regex = new RegExp("(btn$)");  //match all *btn button, which is add to cart
        var regex = new RegExp("btn$");  //match all id=*btn button, which is add to cart
        if ( regex.test(this.id) ){
            var pid = document.getElementById(this.id).getAttribute("pid");
            var item=localStorage.getItem(pid);
            if(!item){ //if NULL, not exist item in localStorage, add 1 
                localStorage.setItem(pid, 1);
            }
            else{ //exist in localStorage
                localStorage.setItem(pid, parseInt(item)+1);
            }
        }
    });


    $(document).on("mouseover","a",function(){   //handle cart hover
        var regex = new RegExp("checkCart");  //get all key value pairs
        if ( regex.test(this.id) ){
            getKeyValue();
            addToCart();
        }
    });

    $(document).on("click","button",function(){   //handle cart hover
        var regex = new RegExp("updateCart");  //get all key value pairs
        if ( regex.test(this.id) ){
            updateLocalStorage();
            getKeyValue();
            addToCart();
        }
    });
});

function addToCart() {
    var xmlhttp;
    var content;
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            content=document.getElementById("cart-body").innerHTML;
            document.getElementById("cart-body").innerHTML=xmlhttp.responseText;
        }

    }
    xmlhttp.open("GET","cart.php?pid="+keys+"&quantity="+values,true); //IMPORTANT!: Google Chrome has weird cache: The following line must be added to prevent caching, otherwise code is fixed
    xmlhttp.setRequestHeader('Cache-Control', 'no-cache');
    xmlhttp.send();
}
function getKeyValue(){
    keys=[]; //empty everything and get value
    values=[];
    for ( var i = 0, len = localStorage.length; i < len; ++i ) {
        keys.push(localStorage.key( i ));
        values.push(localStorage.getItem( localStorage.key( i ) ));
    }
}


function updateLocalStorage(){
    for ( var i = 0, len = localStorage.length; i < len; ++i ) {
        if(isNaN(localStorage.key( i ))){
            continue;
        }
        $quan=document.getElementById(localStorage.key( i )+"quantity").value;
        localStorage.setItem(localStorage.key( i ), $quan);
    }
    for (const key in localStorage) {
        if(isNaN(localStorage.key( i ))){
            continue;
        }
        if(localStorage.getItem(key)==0){
            localStorage.removeItem(key);
        }
    }
}