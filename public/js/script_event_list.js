// Affichage POP-UP :
// for (let i = 0; i < $("article").length; i++) {
//   $(".popup-${i}").hide();
// }

// $(".avatar").on("click", function (e) {
//   $(".popup-${i} .card-title").text($(e.target.previousElementSibling).text());
//   $("#pop-img").attr("src", $(e.target).attr("src"));

//   $(".popup-${i}").css("top", e.target.offsetTop);
//   $(".popup-${i}").show();
// });

// // Fermeture POP-UP :
// $("#popButtons").on("click", function () {
//   $(".popup-${i}").hide();
// });

//------------------------------------------------//
// Bouton Partager

$(".social").hide();

$(".boutons .btn-success").on("click", function (e) {
  $(e.target.nextElementSibling).slideToggle(200);
  setTimeout(function () {
    $(e.target.nextElementSibling).slideUp(200);
  }, 5000);
});
