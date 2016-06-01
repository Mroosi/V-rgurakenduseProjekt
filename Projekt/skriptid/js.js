window.onload = function() {
    var kuva = document.getElementById('log');
    var list = document.getElementById('logbox');
    var rkuva = document.getElementById('reg');
    var rlist = document.getElementById('regbox');
    var regTagasi = document.getElementById('regTagasi');
    
    /*kuvab kas sisselogimise või registreerumise vormi*/
    kuva.addEventListener('click', function(event) {
        list.style.display = 'block';
        rlist.style.display = 'none';
       
    });
    
    rkuva.addEventListener('click', function(event) {
        rlist.style.display = 'block';
        list.style.display = 'none';
       
    });
    
    /*kui registreerumisel on mingi jama siis kuvab registreerumise vormi uuesti tagasi*/
    if (regTagasi && regTagasi.value >0) {
        rlist.style.display = 'block';
        list.style.display = 'none';
    }
}
