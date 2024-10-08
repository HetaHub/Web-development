// paypal.Buttons({
//     // Sets up the transaction when a payment button is clicked
//     createOrder: (data, actions) => {
//       return actions.order.create({
//         purchase_units: [{
//           amount: {
//             value: '77.44' // Can also reference a variable or function
//           }
//         }]
//       });
//     },
//     // Finalize the transaction after payer approval
//     onApprove: (data, actions) => {
//       return actions.order.capture().then(function(orderData) {
//         // Successful capture! For dev/demo purposes:
//         console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
//         const transaction = orderData.purchase_units[0].payments.captures[0];
//         alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
//         // When ready to go live, remove the alert and show a success message within this page. For example:
//         // const element = document.getElementById('paypal-button-container');
//         // element.innerHTML = '<h3>Thank you for your payment!</h3>';
//         // Or go to another URL:  actions.redirect('thank_you.html');
//       });
//     }
// }).render('#paypal-button-container');


let flag=0;
document.getElementById('checkOut').addEventListener('submit', logSubmit);
function logSubmit(event) {
  document.getElementById("checkOut").cmd.value = "_cart";
  document.getElementById("checkOut").business.value = "LSA57EQZL4QSA";
  document.getElementById("checkOut").lc.value = "HK";
  document.getElementById("checkOut").currency_code.value = "HKD";
  document.getElementById("checkOut").upload.value = 1;
  if(flag==0){
    event.preventDefault();
    saveRecord();
  }
  if(flag==1){
    clearCart();
    document.getElementById('updateCart').click();
    flag=0; //set back to 0
    this.target= 'Popup_Window';
    var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=600,left = 312,top = 234');
  }
}
function saveRecord(temp3) {
  var content;
  var data;
  content = document.querySelectorAll('[id$=quantity]');
  if (content) {
    var temp = {};

    for (let i = 0; i < content.length; i++) {
      Object.assign(temp, { [content[i].getAttribute('id')]: content[i].getAttribute('value') });
    }
    data = JSON.stringify(temp);
    p = new Promise(function (resolve, reject) {
      $.ajax({
        type: "POST",
        url: "checkout-process.php",
        async: false,
        data: data, //IMPORTANT: data here is the pid and the qunatity, but below is the respond
        success: function (data) {  //successful will return lastInsertId and digest, the data below is respond from checkout-process.php
          //do what you want here...  
          //window.location.replace("checkout-process.php");
          resolve(data);
          //callBack(data);
          // let returnedResult =temp2.split(/,/);
          // alert(returnedResult[1]);
          //document.myform.myinput.value = '1';


          // document.getElementById("checkOut").custom.value=temp2;
          // document.getElementById("checkOut").submit();


          // var myForm = document.getElementById('checkOut');
          // myForm.custom.value=temp2;
          // myForm.submit();        
        },
        failure: function (data, success, failure) {
          alert("Some problem existed, please try again.");
          reject(null);
        }
      });
    });
    p.then(function (message) {
      document.getElementById("checkOut").custom.value = message;
      flag = 1;
      //p=document.getElementById('checkOut').submit();

    })
      .catch(function () { return null; })
  }
}

function clearCart() {
  var content = document.querySelectorAll('[id$=quantity]');
  for (var i = 0, len = content.length; i < len; i++) {
    content[i].value = 0;
  }
  document.getElementById('updateCart').click();
}
