//Selected new Date
var selected = document.getElementById("new-start-date").value;

// transform time-stamp to readible date
function stampToDate(n){
    y = n.getFullYear();
    m = n.getMonth() + 1;
    d = n.getDate();
    return m + "/" + d + "/" + y;
}

//submitted function:
function submitted() {

    var confirmed = confirm('Are you sure you want to change the pay date?');

    if (confirmed == true){

        document.getElementById('myform').submit(function(e){    

            window.location.href = 'alter_pay.php'; 
        });
        
    }

}



if (typeof(Storage) !== "undefined") {
    localStorage.setItem('myCat', 'Tom');
    var tt = localStorage.getItem('myCat');
    var StartDate = parseInt(localStorage.getItem('startDate'));
    var EndDate = parseInt(localStorage.getItem('dueDate'));
    var PayDate = parseInt(localStorage.getItem('payDate'));
    var control = localStorage.getItem('controll');

    StartDateD = new Date(StartDate);
    EndDateD = new Date(EndDate);


    document.getElementById("start-date").innerHTML = stampToDate(StartDateD);
    document.getElementById("end-date").innerHTML = stampToDate(EndDateD);
    document.getElementById("pay-date").innerHTML = stampToDate(PayDate);

    console.log(tt);
    console.log(control);
    console.log(StartDateD);
    console.log(EndDateD);
    console.log(selected);
  } else {
    // Sorry! No Web Storage support..
    document.getElementById("start-date").innerHTML = "Sorry! Your web browser does not support Web Storage";
    document.getElementById("end-date").innerHTML = "Sorry! Your web browser does not support Web Storage";
  }




