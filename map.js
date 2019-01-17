var old_revelation=null;
var container=null;
var dates=null;
var mymap = null;

function initmap(longitude,latitude){
    mymap=L.map('mapid').setView([longitude, latitude], 15);
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(mymap);
    
    var marker = L.marker([longitude, latitude]).addTo(mymap);
    marker._icon.id="marker-user";
    marker.bindPopup("<b>Vous étes ici !</b>").openPopup();
}

function addMarker(name,lat,lon,avail,free){    
    var new_marker = L.marker([lat, lon]).addTo(mymap);
    new_marker.bindPopup("<b>"+name+"</b></br><p>Disponible:</p>"+avail+"<p>Point de rattachement:</p>"+free);    
}

function revele_info(event){
    if(old_revelation!=null){
        old_revelation.parentNode.getElementsByClassName('info')[0].style.display='none';        
        old_revelation.classList.remove('active');
    }

    old_revelation = event.target;
    old_revelation.parentNode.getElementsByClassName('info')[0].style.display='inline-flex';
    old_revelation.classList.add('active'); 
}

(function fn(){    
    liste_jour = document.getElementsByClassName('jour');    
    for(let element of liste_jour){
        dates = document.getElementsByClassName(element.textContent);        
        for(let date of dates){
            element.parentNode.appendChild(date);
        }                    
    }
})();