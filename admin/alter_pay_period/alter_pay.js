// transform time-stamp to readible date
function stampToDate(n){
    y = n.getFullYear();
    m = n.getMonth() + 1;
    d = n.getDate();
    return m + "/" + d + "/" + y;
}




if (typeof(Storage) !== "undefined") {
    localStorage.setItem('myCat', 'Tom');
    var tt = localStorage.getItem('myCat');
    var StartDate = localStorage.getItem('startDate');
    var EndDate = localStorage.getItem('dueDate');
    var control = localStorage.getItem('controll');

    StartDateDate = new Date(StartDate);



    document.getElementById("start-date").innerHTML = stampToDate( StartDateDate );
    document.getElementById("end-date").innerHTML = new Date(EndDate);

    console.log(tt);
    console.log(control);
    console.log(StartDate);
    console.log(EndDate);
  } else {
    // Sorry! No Web Storage support..
  }




