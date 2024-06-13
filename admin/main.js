let menuButton = document.querySelector(".button-menu");
let container = document.querySelector(".container");
let pageContent = document.querySelector(".page-content");
let responsiveBreakpoint = 991;

if (window.innerWidth <= responsiveBreakpoint) {
  container.classList.add("nav-closed");
}

menuButton.addEventListener("click", function () {
  container.classList.toggle("nav-closed");
});

pageContent.addEventListener("click", function () {
  if (window.innerWidth <= responsiveBreakpoint) {
    container.classList.add("nav-closed");
  }
});

window.addEventListener("resize", function () {
  if (window.innerWidth > responsiveBreakpoint) {
    container.classList.remove("nav-closed");
  }
});

function showTime() {
  let today = new Date();
  let curr_hour = today.getHours();
  let curr_minute = today.getMinutes();
  let curr_second = today.getSeconds();
  if (curr_hour == 0) {
    curr_hour = 12;
  }
  if (curr_hour > 24) {
    curr_hour = curr_hour - 12;
  }
  curr_hour = checkTime(curr_hour);
  curr_minute = checkTime(curr_minute);
  curr_second = checkTime(curr_second);
  document.getElementById(
    "time"
  ).innerHTML = `<strong>Jam hari ini : ${curr_hour}:${curr_minute}:${curr_second} </strong>`;
}

function checkTime(i) {
  if (i < 10) {
    i = "0" + i;
  }
  return i;
}
setInterval(showTime, 500);
$(document).ready(function () {
  $("#tabel-data").DataTable();
});

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('addStockForm');
  const stockTable = document.getElementById('stockTable');

  form.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(form);
      
      fetch('api.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          alert(data);
          loadStockData();
      });
  });

  function loadStockData() {
      fetch('api.php')
      .then(response => response.json())
      .then(data => {
          let tableContent = '<table><tr><th>Produk</th><th>Sisa Stok</th></tr>';
          data.forEach(stock => {
              tableContent += `<tr><td>${stock.product}</td><td>${stock.sisa_stok}</td></tr>`;
          });
          tableContent += '</table>';
          stockTable.innerHTML = tableContent;
      });
  }

  loadStockData();
});
