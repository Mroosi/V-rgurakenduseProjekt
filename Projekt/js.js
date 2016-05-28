window.onload = function() {
var kuva = document.getElementById('log');
var list = document.getElementById('logboxx');
var rkuva = document.getElementById('reg');
var rlist = document.getElementById('regbox');

kuva.addEventListener('click', function(event) {
    list.style.display = 'block';
    rlist.style.display = 'none';
});

rkuva.addEventListener('click', function(event) {
    rlist.style.display = 'block';
    list.style.display = 'none';
});

}
