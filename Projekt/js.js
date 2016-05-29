window.onload = function() {

var kuva = document.getElementById('log');
var list = document.getElementById('logboxx');
var rkuva = document.getElementById('reg');
var rlist = document.getElementById('regbox');
var lr = document.getElementById('6').value;
/*kuvab kas sisselogimuse või registreerumise vormi*/
kuva.addEventListener('click', function(event) {
    list.style.display = 'block';
    rlist.style.display = 'none';
});
rkuva.addEventListener('click', function(event) {
    rlist.style.display = 'block';
    list.style.display = 'none';
});
/*kui registreerumisel on mingi jama siis kuvab registreerumise vormi uuesti tagasi*/
if (lr > 0) {
    rlist.style.display = 'block';
    list.style.display = 'none';
}


}
