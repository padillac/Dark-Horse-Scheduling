
// get today's date
n =  new Date();
y = n.getFullYear();
m = n.getMonth() + 1;
d = n.getDate();
document.getElementById("date").innerHTML = m + "/" + d + "/" + y;



// find next due date
var timeStampNow = Date.now();
var timeStamp = new Date("2021-07-09 23:59:59");
var timeStampStart = timeStamp.getTime();

console.log(timeStampNow);
console.log(timeStampStart);
for (let i = 0; i < 200; i++) {
    

}




document.getElementById("dueDate").innerHTML = "dummy Date";



